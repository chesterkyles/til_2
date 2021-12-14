# User Management

The admin user management API gives you the ability to programmatically retrieve, create, update, and delete users without requiring a user’s existing credentials and without worrying about client-side rate limiting.

## Initializing the Auth component

```php
 $auth = app('firebase.auth');
```

## Get information about a specific User

```php
try {
    $user = $auth->getUser('some-uid');
    $user = $auth->getUserByEmail('user@domain.tld');
    $user = $auth->getUserByPhoneNumber('+49-123-456789');
} catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
    echo $e->getMessage();
}
```

### Create a User

The Admin SDK provides a method that allows you to create a new Firebase Authentication user. This method accepts an object containing the profile information to include in the newly created user account:

```php
$userProperties = [
    'email' => 'user@example.com',
    'emailVerified' => false,
    'phoneNumber' => '+15555550100',
    'password' => 'secretPassword',
    'displayName' => 'John Doe',
    'photoUrl' => 'http://www.example.com/12345678/photo.png',
    'disabled' => false,
];

$createdUser = $auth->createUser($userProperties);

// This is equivalent to:

$request = \Kreait\Auth\Request\CreateUser::new()
    ->withUnverifiedEmail('user@example.com')
    ->withPhoneNumber('+15555550100')
    ->withClearTextPassword('secretPassword')
    ->withDisplayName('John Doe')
    ->withPhotoUrl('http://www.example.com/12345678/photo.png');

$createdUser = $auth->createUser($request);
```

By default, Firebase Authentication will generate a random uid for the new user. If you instead want to specify your own uid for the new user, you can include in the properties passed to the user creation method:

```php
$properties = [
    'uid' => 'some-uid',
    // other properties
];

$request = \Kreait\Auth\Request\CreateUser::new()
    ->withUid('some-uid')
    // with other properties
;
```

Any combination of the following properties can be provided:

Property | Type | Description
-------- | ---- | -----------
`uid` | string | The uid to assign to the newly created user. Must be a string between 1 and 128 characters long, inclusive. If not provided, a random uid will be automatically generated.
`email` | string | The user’s primary email. Must be a valid email address.
`emailVerified` | boolean | Whether or not the user’s primary email is verified. If not provided, the default is false.
`phoneNumber` | string | The user’s primary phone number. Must be a valid E.164 spec compliant phone number.
`password` | string | The user’s raw, unhashed password. Must be at least six characters long.
`displayName` | string | The users’ display name.
`photoURL` | string | The user’s photo URL.
`disabled` | boolean | Whether or not the user is disabled. true for disabled; false for enabled. If not provided, the default is false.

You can read full documentation of Firebase Admin SDK [here](https://firebase-php.readthedocs.io/en/latest/user-management.html).
