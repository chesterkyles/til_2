# Http Responses

An HTTP response is made by a server to a client. The aim of the response is to provide the client with the resource it requested, or inform the client that the action it requested has been carried out; or else to inform the client that an error occurred in processing its request.

## Creating Responses

### Strings and Arrays

All routes and controllers should return a response to be sent back to the user's browser.

```php
// strings
Route::get('/', function () {
    return 'Hello World';
});

// arrays
Route::get('/', function () {
    return [1, 2, 3];
});
```

### Response Objects

Routes and/or controllers return full `Illuminate\Http\Response` instances or views instead of strings and/or arrays. A `Response` instance inherits from the `Symfony\Component\HttpFoundation\Response` class, which provides a variety of methods for building HTTP responses:

```php
Route::get('/home', function () {
    return response('Hello World', 200)
            ->header('Content-Type', 'text/plain');
});
```

### Eloquent Model and Collections

Route and/or controllers directly return Eloquent ORM models and collections. Laravel will automatically convert the models and collections to JSON responses while respecting the model's hidden attributes:

```php
use App\Models\User;

Route::get('/user/{user}', function (User $user) {
    return $user;
});
```

### Attaching Headers to Responses

Use the `header` or `withHeaders` method to add a series of headers to the response before sending it back to the user:

```php
// chain header methods
return response($content)
            ->header('Content-Type', $type)
            ->header('X-Header-One', 'Header Value')
            ->header('X-Header-Two', 'Header Value');

// array of headers
return response($content)
            ->withHeaders([
                'Content-Type' => $type,
                'X-Header-One' => 'Header Value',
                'X-Header-Two' => 'Header Value',
            ]);
```

### Attaching Cookies to Responses

Use the `cookie` method to attach a cookie to an outgoing `Illuminate\Http\Response`. Pass the name, value, and the number of minutes the cookie should be considered valid to this method:

```php
return response('Hello World')->cookie(
    'name', 'value', $minutes
);
```

- Read more about attaching cookies [here](https://laravel.com/docs/8.x/responses#attaching-cookies-to-responses).

## Redirects

Redirect responses are instances of the `Illuminate\Http\RedirectResponse` class, and contain the proper headers needed to redirect the user to another URL. There are several ways to generate a `RedirectResponse` instance. The simplest method is to use the global `redirect` helper:

```php
Route::get('/dashboard', function () {
    return redirect('home/dashboard');
});
```

Sometimes you may wish to redirect the user to their previous location, such as when a submitted form is invalid using global `back` helper function:

```php
Route::post('/user/profile', function () {
    // Validate the request...

    return back()->withInput();
});
```

### Redirecting to named routes

For example, redirect to a named route `login`:

```php
return redirect()->route('login');

// For a route with the following URI: /profile/{id}
return redirect()->route('profile', ['id' => 1]);

// via Eloquent models
return redirect()->route('profile', [$user]);
```

If you would like to customize the value that is placed in the route parameter, you can specify the column in the route parameter definition (`/profile/{id:slug}`) or you can override the `getRouteKey` method on your Eloquent model:

```php
/**
 * Get the value of the model's route key.
 *
 * @return mixed
 */
public function getRouteKey()
{
    return $this->slug;
}
```

### Redirecting to controller actions

```php
use App\Http\Controllers\UserController;

return redirect()->action([UserController::class, 'index']);

// if controller route requires parameters
return redirect()->action(
    [UserController::class, 'profile'], ['id' => 1]
);
```

### Redirecting to external domain

```php
return redirect()->away('https://www.google.com');
```

### Redirecting to flashed session data

Redirecting to a new URL and flashing data to the session are usually done at the same time:

```php
Route::post('/user/profile', function () {
    // ...

    return redirect('dashboard')->with('status', 'Profile updated!');
});
```

Display the flashed message from the session:

```php
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
```

### Redirecting with input

This is typically done when the user has encountered validation error.

```php
return back()->withInput();
```

## Other response types

The `response` helper may be used to generate other types of response instances. When the `response` helper is called without arguments, an implementation of the `Illuminate\Contracts\Routing\ResponseFactory` contract is returned. This contract provides several helpful methods for generating responses.

### View Response

```php
return response()
            ->view('hello', $data, 200)
            ->header('Content-Type', $type);
```

### JSON Response

```php
return response()->json([
    'name' => 'Abigail',
    'state' => 'CA',
]);

//JSONP response
return response()
            ->json(['name' => 'Abigail', 'state' => 'CA'])
            ->withCallback($request->input('callback'));
```

### File Downloads

The `download` method may be used to generate a response that forces the user's browser to download the file at the given path. The `download` method accepts a filename as the second argument to the method, which will determine the filename that is seen by the user downloading the file. Finally, you may pass an array of HTTP headers as the third argument to the method:

```php
return response()->download($pathToFile);

return response()->download($pathToFile, $name, $headers);
```

### File Response

The `file` method may be used to display a file, such as an image or PDF, directly in the user's browser instead of initiating a download.

```php
return response()->file($pathToFile);

return response()->file($pathToFile, $headers);
```

You may read more about [responses](https://laravel.com/docs/8.x/responses) on the official Laravel documentation.
