# Error Handling

## Introduction

When starting a new Laravel project, error and exception handling is already configured. The `App\Exceptions\Handler` class is where all exceptions thrown by the application are logged and then rendered to the user.

## Configuration

The `debug` option in your `config/app.php` configuration file determines how much information about an error is actually displayed to the user. By default, this option is set to respect the value of the `APP_DEBUG` environment variable, which is stored in your `.env` file.

During local development, you should set the `APP_DEBUG` environment variable to `true`. In your production environment, this value should always be `false`. If the value is set to `true` in production, you risk exposing sensitive configuration values to your application's end users.

## The Exception Handler

### Reporting Exceptions

All exceptions are handled by the `App\Exceptions\Handler` class which contains `register` method where you may register custom exception reporting and rendering callbacks.

```php
use App\Exceptions\InvalidOrderException;

/**
 * Register the exception handling callbacks for the application.
 *
 * @return void
 */
public function register()
{
    $this->reportable(function (InvalidOrderException $e) {
        //
    });
}
```

_Note: You may use `stop` method or return `false` from the callback to stop logging the exception using the default logging configuration._

You may read more about [reporting exceptions](https://laravel.com/docs/8.x/errors#reporting-exceptions) on the official Laravel documentation.

### Ignoring Exceptions by Type

The exception handler contains a `$dontReport` property which is initialized to an empty array. Any classes that is added in the property will never be reported. For example:

```php
use App\Exceptions\InvalidOrderException;

protected $dontReport = [
    InvalidOrderException::class,
];
```

### Rendering Exceptions

Laravel exception handler, by default, will convert exceptions into an HTTP response. However, it is customizable for exceptions of a given type via the `renderable` method.
The closure passed to the `renderable` method should return an instance of `Illuminate\Http\Response`, which may be generated via the `response` helper. For example:

```php
use App\Exceptions\InvalidOrderException;

public function register()
{
    $this->renderable(function (InvalidOrderException $e, $request) {
        return response()->view('errors.invalid-order', [], 500);
    });
}
```

The `renderable` method can be used to override the rendering behavior for built-in Laravel or Symfony exceptions such as `NotFoundHttpException`. For example:

```php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

public function register()
{
    $this->renderable(function (NotFoundHttpException $e, $request) {
        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Record not found.'
            ], 404);
        }
    });
}
```

### Reportable and Renderable Exceptions

Instead of type-checking exceptions in the `register` method, you may define `report` and `render` methods directly on the custom exceptions. When these methods exist, they will be automatically called by the framework:

```php
<?php

namespace App\Exceptions;

use Exception;

class InvalidOrderException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response(...);
    }
}
```

_Note: You may return `false` for certain conditions in exceptions with custom reporting logic. This instruct Laravel to report the exceptions using the default exception handling configuration._

## HTTP Exceptions

Some exceptions describe HTTP error codes from the server. For example, this may be a "page not found" error (404), an "unauthorized error" (401) or even a developer generated 500 error. In order to generate such a response from anywhere in your application, you may use the `abort` helper

```php
abort(404)
```

### Custom HTTP Error Pages

Laravel makes it easy to display custom error pages for various HTTP status codes. Just create a `resources/views/errors/404.blade.php` file to customize the error page for 404 HTTP status codes. The `Symfony\Component\HttpKernel\Exception\HttpException` instance raised by the `abort` function will be passed to the view as an `$exception` variable:

```php
<h2>{{ $exception->getMessage() }}</h2>
```

You may publish Laravel's default error page templates using the `vendor:publish` Artisan command. Once the templates have been published, you may customize them to your liking:

```bash
php artisan vendor:publish --tag=laravel-errors
```
