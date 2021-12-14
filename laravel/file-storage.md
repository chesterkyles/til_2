# File Storage

## Configuration

Laravel's filesystem configuration file is located at `config/filesystems.php`. Within this file, you may configure all of your filesystem "disks". Each disk represents a particular storage driver and storage location.

The `local` driver interacts with files stored locally on the server running the Laravel application while the `s3` driver is used to write to Amazon's S3 cloud storage service.

### Local Driver

When using the `local` driver, all file operations are relative to the `root` directory defined in your `filesystems` configuration file. By default, this value is set to the `storage/app` directory. Therefore, the following method would write to `storage/app/example.txt`:

```php
use Illuminate\Support\Facades\Storage;

Storage::disk('local')->put('example.txt', 'Contents');
```

### Public Disk

The `public` disk included in your application's `filesystems` configuration file is intended for files that are going to be publicly accessible. By default, the `public` disk uses the `local` driver and stores its files in `storage/app/public`.

To make these files accessible from the web, you should create a symbolic link from `public/storage` to `storage/app/public`. To create the symbolic link, you may use the `storage:link` Artisan command:

```sh
php artisan storage:link
```

Once a file has been stored and the symbolic link has been created, you can create a URL to the files using the `asset` helper:

```php
echo asset('storage/file.txt');
```

You may configure additional symbolic links in your `filesystems` configuration file. Each of the configured links will be created when you run the `storage:link` command:

```php
'links' => [
    public_path('storage') => storage_path('app/public'),
    public_path('images') => storage_path('app/images'),
],
```

### Driver Prerequisites

Before using the S3 or SFTP drivers, you will need to install the appropriate package via the Composer package manager:

- Amazon S3: `composer require --with-all-dependencies league/flysystem-aws-s3-v3 "^1.0`
- SFTP: `composer require league/flysystem-sftp "~1.0"`
You may choose to install a cached adapter for increased performance:
- CachedAdapter: `composer require league/flysystem-cached-adapter "~1.0"`

Read more about drivers and driver configurations [here](https://laravel.com/docs/8.x/filesystem#driver-prerequisites)

## Obtaining Disk

The `Storage` facade may be used to interact with any of your configured disks. For example, you may use the `put` method on the facade to store an avatar on the default disk. If you call methods on the `Storage` facade without first calling the `disk` method, the method will automatically be passed to the default disk:

```php
use Illuminate\Support\Facades\Storage;

Storage::put('avatars/1', $content);
```

If your application interacts with multiple disks, you may use the `disk` method on the `Storage` facade to work with files on a particular disk:

```php
use Illuminate\Support\Facades\Storage;
Storage::disk('s3')->put('avatars/1', $content);

// to create a disk at runtime
$disk = Storage::build([
    'driver' => 'local',
    'root' => '/path/to/root',
]);

$disk->put('image.jpg', $content);
```

## Retrieving Files

The `get` method may be used to retrieve the contents of a file. The raw string contents of the file will be returned by the method. Remember, all file paths should be specified relative to the disk's "root" location:

```php
$contents = Storage::get('file.jpg');

// exists() - to determine if a file exists on a disk
if (Storage::disk('s3')->exists('file.jpg')) {
    // ...
}

// missing() - to determine if a file is missing from the disk
if (Storage::disk('s3')->missing('file.jpg')) {
    // ...
}
```

### Download Files

The `download` method may be used to generate a response that forces the user's browser to download the file at the given path:

```php
return Storage::download('file.jpg');

// $name - filename seen by the user downloading the file
return Storage::download('file.jpg', $name, $headers);
```

### File URLs

Use the `url` method to get the URL for a given file. If you are using the `local` driver, this will typically just prepend `/storage` to the given path and return a relative URL to the file. If you are using the `s3` driver, the fully qualified remote URL will be returned:

```php
use Illuminate\Support\Facades\Storage;

$url = Storage::url('file.jpg');

// temporary URL, the DateTime instance specifies
$url = Storage::temporaryUrl(
    'file.jpg', now()->addMinutes(5)
);
```

### File Metadata

In addition to reading and writing files, Laravel can also provide information about the files themselves such as:

```php
use Illuminate\Support\Facades\Storage;

// get the size of a file in bytes
$size = Storage::size('file.jpg');

// returns the UNIX timestamp of the last time the file was modified
$time = Storage::lastModified('file.jpg');

// get the path for a given file
$path = Storage::path('file.jpg');
```

## Storing Files

The `put` method may be used to store file contents on a disk. Remember, all file paths should be specified relative to the "root" location configured for the disk:

```php
use Illuminate\Support\Facades\Storage;

Storage::put('file.jpg', $contents);
Storage::put('file.jpg', $resource);

// to specify the visibility of the file
Storage::putFile('photos', new File('/path/to/photo'), 'public');

// prepending and appending text
Storage::prepend('file.log', 'Prepended Text');
Storage::append('file.log', 'Appended Text');
```

## File Uploads

Laravel makes it very easy to store uploaded files using the `store` method on an uploaded file instance. Call the `store` method with the path at which you wish to store the uploaded file:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAvatarController extends Controller
{
    /**
     * Update the avatar for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $path = $request->file('avatar')->store('avatars');

        return $path;
    }
}
```

You may also call the `putFile` method on the `Storage` facade to perform the same file storage operation as the example above:

```php
$path = Storage::putFile('avatars', $request->file('avatar'));

// Specifying a filename
$path = $request->file('avatar')->storeAs(
    'avatars', $request->user()->id
);

$path = Storage::putFileAs(
    'avatars', $request->file('avatar'), $request->user()->id
);

// Specifying a disk
$path = $request->file('avatar')->store(
    'avatars/'.$request->user()->id, 's3'
);

$path = $request->file('avatar')->storeAs(
    'avatars',
    $request->user()->id,
    's3'
);
```

### Other File information

```php
// get the original name of the uploaded file
$name = $request->file('avatar')->getClientOriginalName();

// get the file extension
$extension = $request->file('avatar')->extension();
```

You may want to read more about [file storage](https://laravel.com/docs/8.x/filesystem) in the official Laravel documentation.
