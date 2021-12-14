# HTTP Tests

## Getting Started

Laravel provides a very fluent API for making HTTP requests to your application and examining the responses. For example, take a look at the feature test defined below:

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_basic_request()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
```

The `get` method makes a `GET` request into the application, while the `assertStatus` method asserts that the returned response should have the given HTTP status code. In addition to this simple assertion, Laravel also contains a variety of assertions for inspecting the response headers, content, JSON structure, and more.

## Requests

To make a request to your application, you may invoke the `get`, `post`, `put`, `patch`, or `delete` methods within your test. See above example for `get` request.

### Customizing Request Headers

Use `withHeaders` method to customize the request's headers before it is sent to the application.

```php
    $response = $this->withHeaders([
        'X-Header' => 'Value',
    ])->post('/user', ['name' => 'Sally']);
```

### Cookie

Use `withCookie` or `withCookies` methods to set cookie value before making a request.

```php
    $response = $this->withCookie('color', 'blue')->get('/');

    $response = $this->withCookies([
        'color' => 'blue',
        'name' => 'Taylor',
    ])->get('/');
```

### Session/Authentication

Set the session data to a given array using the `withSession` method. This is useful for loading the session with data before issuing a request to the application.

```php
    $response = $this->withSession(['banned' => false])->get('/');
```

Laravel's session is typically used to maintain state for the currently authenticated user. Therefore, the `actingAs` helper method provides a simple way to authenticate a given user as the current user. For example, we may use a [model factory](https://laravel.com/docs/8.x/database-testing#defining-model-factories) to generate and authenticate a user:

```php
    $user = User::factory()->create();

    $response = $this->actingAs($user)
                     ->withSession(['banned' => false])
                     ->get('/');
```

Also, specify which guard should be used to authenticate the given user by passing the guard name as the second argument to the `actingAs` method

```php
    $this->actingAs($user, 'api')
```

## Debugging Responses

After making a test request to your application, the `dump`, `dumpHeaders`, and `dumpSession` methods may be used to examine and debug the response contents:

```php
    $response->dumpHeaders();
    $response->dumpSession();
    $response->dump();
```

## Testing JSON APIs

Laravel also provides several helpers for testing JSON APIs and their responses. For example, the `json`, `getJson`, `postJson`, `putJson`, `patchJson`, `deleteJson`, and `optionsJson` methods may be used to issue JSON requests with various HTTP verbs.

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test_making_an_api_request()
    {
        $response = $this->postJson('/api/user', ['name' => 'Sally']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }
}
```

In addition, JSON response data may be accessed as array variables on the response, making it convenient for you to inspect the individual values returned within a JSON response:

```php
    $this->assertTrue($response['created']);
```

Read more about testing JSON APIs on the official Laravel documentation. Click [here](https://laravel.com/docs/8.x/http-tests#testing-json-apis).

## Testing File Uploads

The `Illuminate\Http\UploadedFile` class provides a `fake` method which may be used to generate dummy files or images for testing. This, combined with the `Storage` facade's `fake` method, greatly simplifies the testing of file uploads.

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_avatars_can_be_uploaded()
    {
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post('/avatar', [
            'avatar' => $file,
        ]);

        Storage::disk('avatars')->assertExists($file->hashName());
    }
}
```

If you would like to assert that a given file does not exist, you may use the `assertMissing` method provided by the `Storage` facade:

```php
Storage::fake('avatars');

// ...

Storage::disk('avatars')->assertMissing('missing.jpg');
```

Read more about testing File Uploads on the official Laravel documentation. Click [here](https://laravel.com/docs/8.x/http-tests#testing-file-uploads).

## Testing Views

Laravel also allows you to render a view without making a simulated HTTP request to the application. To accomplish this, you may call the `view` method within your test. The `view` method accepts the view name and an optional array of data. The method returns an instance of `Illuminate\Testing\TestView`, which offers several methods to conveniently make assertions about the view's contents:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_a_welcome_view_can_be_rendered()
    {
        $view = $this->view('welcome', ['name' => 'Taylor']);

        $view->assertSee('Taylor');
    }
}
```

The `TestView` class provides the following assertion methods: `assertSee`, `assertSeeInOrder`, `assertSeeText`, `assertSeeTextInOrder`, `assertDontSee`, and `assertDontSeeText`.

If needed, you may get the raw, rendered view contents by casting the `TestView` instance to a string:

```php
    $contents = (string) $this->view('welcome');
```

### Sharing Errors

Some views may depend on errors shared in the [global error bag](https://laravel.com/docs/8.x/validation#quick-displaying-the-validation-errors) provided by Laravel. To hydrate the error bag with error messages, you may use the `withViewErrors` method:

```php
    $view = $this->withViewErrors([
        'name' => ['Please provide a valid name.']
    ])->view('form');

    $view->assertSee('Please provide a valid name.');
```

### Rendering Blade and Components

If necessary, you may use the `blade` method to evaluate and render a raw Blade string. Like the `view` method, the `blade` method returns an instance of `Illuminate\Testing\TestView`:

```php
    $view = $this->blade(
        '<x-component :name="$name" />',
        ['name' => 'Taylor']
    );

    $view->assertSee('Taylor');
```

You may use the `component` method to evaluate and render a Blade component. Like the `view` method, the `component` method returns an instance of `Illuminate\Testing\TestView`:

```php
    $view = $this->component(Profile::class, ['name' => 'Taylor']);

    $view->assertSee('Taylor');
```

## Avaliable Assertions

Read official Laravel documentation for the [complete list](https://laravel.com/docs/8.x/http-tests#available-assertions) of available assertions. Some of the available assertions are the following:

- assertJson

```php
    $response->assertJson(array $data, $strict = false);
```

- assertNotFound

```php
    $response->assertNotFound();
```

- assertOk

```php
    $response->assertOk();
```
