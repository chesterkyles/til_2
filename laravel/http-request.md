# HTTP Requests

Laravel's `Illuminate\Http\Request` class provides an object-oriented way to interact with the current HTTP request being handled by your application as well as retrieve the input, cookies, and files that were submitted with the request.

## Example

Mostly, HTTP requests are used when invoking and receiving request methods from or to API endpoints. See example [here](src/sample_middleware_request.php).

## Interacting with the Request

### Accessing the Request

To obtain an instance of the current HTTP request via dependency injection, you should type-hint the `Illuminate\Http\Request` class on your route closure or controller method. The incoming request instance will automatically be injected by the Laravel service container:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');

        //
    }
}
```

As mentioned, you may also type-hint the `Illuminate\Http\Request` class on a route closure. The service container will automatically inject the incoming request into the closure when it is executed:

```php
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    //
});
```

#### Dependency Injection and Route Parameters

For example:

```php
use App\Http\Controllers\UserController;

Route::put('/user/{id}', [UserController::class, 'update']);
```

You may still type-hint the `Illuminate\Http\Request` and access your `id` route parameter by defining your controller method as follows:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
```

### Request Path and Method

The `Illuminate\Http\Request` instance provides a variety of methods for examining the incoming HTTP request and extends the `Symfony\Component\HttpFoundation\Request` class.

#### Retrieving the Request Path

```php
$uri = $request->path();
```

#### Inspecting The Request Path / Route

```php
// verify that the incoming request path matches a given pattern
if ($request->is('admin/*')) {
    //
}

// verify that the incoming request has matched a named route
if ($request->routeIs('admin.*')) {
    //
}
```

#### Retrieving the Request URL

```php
$url = $request->url();

// includes a query string
$urlWithQueryString = $request->fullUrl();

// append query string to the current URL
$request->fullUrlWithQuery(['type' => 'phone']);
```

#### Retrieving the Request Method

```php
$method = $request->method();

// verify that the verb matches a given string
if ($request->isMethod('post')) {
    //
}
```

### Request Headers

You may retrieve a request header from the `Illuminate\Http\Request` instance using the `header` method. If the header is not present on the request, `null` will be returned. However, the `header` method accepts an optional second argument that will be returned if the header is not present on the request:

```php
$value = $request->header('X-Header-Name');

$value = $request->header('X-Header-Name', 'default');

// to determine if the request contains a given header
if ($request->hasHeader('X-Header-Name')) {
    //
}

// retrieve bearer token from the Authorization header; empty string if no such header
$token = $request->bearerToken();
```

### Request IP Address

The `ip` method may be used to retrieve the IP address of the client that made the request to your application:

```php
$ipAddress = $request->ip();
```

## Input

### Retrieving Input

#### Retrieving all Input data

```php
$input = $request->all();
```

#### Retrieving an Input value

```php
$name = $request->input('name');

// pass a default value as second argument
$name = $request->input('name', 'Sally');

// contains array input
$name = $request->input('products.0.name');

$names = $request->input('products.*.name');

// to retrieve all input as associative array
$input = $request->input();
```

#### Retrieving Input from the Query String

```php
$name = $request->query('name');

// return second argument if query string value data is not present
$name = $request->query('name', 'Helen');

// to retrieve all of the query string  values as associative array
$query = $request->query();
```

#### Retrieving JSON Input values

When sending JSON requests to your application, you may access the JSON data via the `input` method as long as the `Content-Type` header of the request is properly set to `application/json`. You may even use "dot" syntax to retrieve values that are nested within JSON arrays:

```php
$name = $request->input('user.name');
```

#### Retrieving Boolean Input values

```php
$archived = $request->boolean('archived');
```

#### Retrieving Input via Dynamic Properties

For example, if one of the application's form contains a `name` field, you may access the value of the field like so:

```php
$name = $request->name;
```

#### Retrieving a portion of the Input data

```php
$input = $request->only(['username', 'password']);

$input = $request->only('username', 'password');

$input = $request->except(['credit_card']);

$input = $request->except('credit_card');
```

### Determining if Input is Present

```php
if ($request->has('name')) {
    //
}

// determine if all specified values in the array are present
if ($request->has(['name', 'email'])) {
    //
}

// execute the given closure if a value is present
$request->whenHas('name', function ($input) {
    //
});

// returns true if any of the specified values are present
if ($request->hasAny(['name', 'email'])) {
    //
}

// determine if a value is present and not empty
if ($request->filled('name')) {
    //
}

// execute the given closure if a value is present and not empty
$request->whenFilled('name', function ($input) {
    //
});

// determine if a given key is absent
if ($request->missing('name')) {
    //
}
```

### Old input

#### Flasing Input to the Session

```php
$request->flash();

$request->flashOnly(['username', 'email']);

$request->flashExcept('password');
```

#### Flasing Input then Redirecting

```php
return redirect('form')->withInput();

return redirect()->route('user.create')->withInput();

return redirect('form')->withInput(
    $request->except('password')
);
```

#### Retrieving old input

```php
$username = $request->old('username');
```

To display old input within a Blade template, use `old` helper to repopulate the form. If no old input exists for the given field, `null` will be returned:

```html
<input type="text" name="username" value="{{ old('username') }}">
```

## Files

### Retrieving Uploaded Files

The `file` method returns an instance of the `Illuminate\Http\UploadedFile` class, which extends the PHP `SplFileInfo` class and provides a variety of methods for interacting with the file:

```php
$file = $request->file('photo');

$file = $request->photo;

// determine if the file is present
if ($request->hasFile('photo')) {
    //
}
```

#### Validating Successful Uploads

```php
if ($request->file('photo')->isValid()) {
    //
}
```

#### File paths and extensions

```php
$path = $request->photo->path();

$extension = $request->photo->extension();
```

### Storing Uploaded Files

```php
$path = $request->photo->store('images');

$path = $request->photo->store('images', 's3');

// storeAs - so that filename will not be automatically generated
$path = $request->photo->storeAs('images', 'filename.jpg');

$path = $request->photo->storeAs('images', 'filename.jpg', 's3');
```

This topic is discussed more in [File Storage](file-storage.md) section.

You may want to read more about HTTP request. Read [here](https://laravel.com/docs/8.x/requests) in the official Laravel documentation.
