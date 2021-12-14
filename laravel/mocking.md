# Mocking

When testing Laravel applications, you may wish to "mock" certain aspects of your application so they are not actually executed during a given test. Laravel provides helpful methods for mocking events, jobs, and other facades out of the box. These helpers primarily provide a convenience layer over Mockery so you do not have to manually make complicated Mockery method calls.

## Example

- See example source code that I have written. [sample_mocking.php](src/sample_mocking.php)

## Mocking Objects

When mocking an object that is going to be injected into your application via Laravel's service container, you will need to bind your mocked instance into the container as an `instance` binding. This will instruct the container to use your mocked instance of the object instead of constructing the object itself:

```php
use App\Service;
use Mockery;
use Mockery\MockInterface;

public function test_something_can_be_mocked()
{
    $this->instance(
        Service::class,
        Mockery::mock(Service::class, function (MockInterface $mock) {
            $mock->shouldReceive('process')->once();
        })
    );
}
```

An equivalent to the example above using `mock` method:

```php
use App\Service;
use Mockery\MockInterface;

$mock = $this->mock(Service::class, function (MockInterface $mock) {
    $mock->shouldReceive('process')->once();
});
```

Use `partialMock` when only need to mock a few methods of an object. The methods that are not mocked will be executed normally when called:

```php
use App\Service;
use Mockery\MockInterface;

$mock = $this->partialMock(Service::class, function (MockInterface $mock) {
    $mock->shouldReceive('process')->once();
});
```

Use `spy` as a Convenient wrapper around the `Mockery::spy` method. Spies are similar to mocks; however, spies record any interaction between the spy and the code being tested, allowing you to make assertions after the code is executed:

```php
use App\Service;

$spy = $this->spy(Service::class);

// ...

$spy->shouldHaveReceived('process');
```

### Expectation Declaration

### Method Call

```php
$mock->shouldReceive('name_of_method');

$mock->shouldReceive('name_of_method_1', 'name_of_method_2');

$mock->shouldReceive([
    'name_of_method_1' => 'return value 1',
    'name_of_method_2' => 'return value 2',
]);

// not expect a call
$mock->shouldNotReceive('name_of_method');
```

### Method Arguments

```php
$mock->shouldReceive('name_of_method')
    ->with($arg1, $arg2, ...);

$mock->shouldReceive('name_of_method')
    ->withArgs([$arg1, $arg2, ...]);

// with closure function
$mock->shouldReceive('foo')->withArgs(function ($arg) {
    if ($arg % 2 == 0) {
        return true;
    }
    return false;
});

// with any or no args
$mock->shouldReceive('name_of_method')
    ->withAnyArgs();

$mock->shouldReceive('name_of_method')
    ->withNoArgs();

// usage:
$mock->foo(4);
```

### Return Value

```php
$mock->shouldReceive('name_of_method')
    ->andReturn($value);

$mock->shouldReceive('name_of_method')
    ->andReturn($value1, $value2, ...)

$mock->shouldReceive('name_of_method')
    ->andReturnValues([$value1, $value2, ...])

// return null
$mock->shouldReceive('name_of_method')
    ->andReturnNull();

// with closure function
$mock->shouldReceive('name_of_method')
    ->andReturnUsing(closure, ...);

// return argument
$mock->shouldReceive('name_of_method')
    ->andReturnArg(1);

// return self when mocking fluid interfaces
$mock->shouldReceive('name_of_method')
    ->andReturnSelf();
```

### Throwing Exceptions

```php
$mock->shouldReceive('name_of_method')
    ->andThrow(new Exception);

// with message and/or code
$mock->shouldReceive('name_of_method')
    ->andThrow('exception_name', 'message', 123456789);
```

### Setting Pulbic Properties

```php
$mock->shouldReceive('name_of_method')
    ->andSet($property, $value);

// or
$mock->shouldReceive('name_of_method')
    ->set($property, $value);
```

### Call Counts

```php
// called zero or more times
$mock->shouldReceive('name_of_method')
    ->zeroOrMoreTimes();

// number of calls
$mock->shouldReceive('name_of_method')
    ->times($n);

// one time only
$mock->shouldReceive('name_of_method')
    ->once();

// two times only
$mock->shouldReceive('name_of_method')
    ->twice();

// never called
$mock->shouldReceive('name_of_method')
    ->never();

// modifiers
// atLeast(), atMost(), between( , )
$mock->shouldReceive('name_of_method')
    ->atLeast()
    ->times(3);
```

You may read more about expectations on this link: <http://docs.mockery.io/en/latest/reference/expectations.html>

## Mail Fake

You may use the `Mail` facade's `fake` method to prevent mail from being sent. Typically, sending mail is unrelated to the code you are actually testing. After calling the `Mail` facade's `fake` method, you may then assert that mailables were instructed to be sent to users and even inspect the data the mailables received:

```php
<?php

namespace Tests\Feature;

use App\Mail\OrderShipped;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_orders_can_be_shipped()
    {
        Mail::fake();

        // Perform order shipping...

        // Assert that no mailables were sent...
        Mail::assertNothingSent();

        // Assert that a mailable was sent...
        Mail::assertSent(OrderShipped::class);

        // Assert a mailable was sent twice...
        Mail::assertSent(OrderShipped::class, 2);

        // Assert a mailable was not sent...
        Mail::assertNotSent(AnotherMailable::class);
    }
}
```

## Storage Fake

The `Storage` facade's `fake` method allows you to easily generate a fake disk that, combined with the file generation utilities of the `Illuminate\Http\UploadedFile` class, greatly simplifies the testing of file uploads. For example:

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
    public function test_albums_can_be_uploaded()
    {
        Storage::fake('photos');

        $response = $this->json('POST', '/photos', [
            UploadedFile::fake()->image('photo1.jpg'),
            UploadedFile::fake()->image('photo2.jpg')
        ]);

        // Assert one or more files were stored...
        Storage::disk('photos')->assertExists('photo1.jpg');
        Storage::disk('photos')->assertExists(['photo1.jpg', 'photo2.jpg']);

        // Assert one or more files were not stored...
        Storage::disk('photos')->assertMissing('missing.jpg');
        Storage::disk('photos')->assertMissing(['missing.jpg', 'non-existing.jpg']);
    }
}
```

For more info about mocking, please check the following links below:

- <https://laravel.com/docs/8.x/mocking>
- <http://docs.mockery.io/en/latest/reference/index.html>
