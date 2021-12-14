# Authentication

## From Official Document

- [Introduction to the Admin Database API](https://firebase.google.com/docs/database/admin/start)
- [Create custom tokens](https://firebase.google.com/docs/auth/admin/create-custom-tokens)
- [Verify ID Tokens](https://firebase.google.com/docs/auth/admin/verify-id-tokens)
- [Manage Session Cookies](https://firebase.google.com/docs/auth/admin/manage-cookies)
- [Revoke refresh tokens](https://firebase.google.com/docs/auth/admin/manage-sessions#revoke_refresh_tokens)

## Initializing the Auth component

```php
 $auth = app('firebase.auth');
```

## Create custom tokens

```php
$uid = 'some-uid';
$customToken = $auth->createCustomToken($uid);
```

You can also optionally specify additional claims to be included in the custom token. For example:

```php
$uid = 'some-uid';
$additionalClaims = [
    'premiumAccount' => true
];

$customToken = $auth->createCustomToken($uid, $additionalClaims);
$customTokenString = $customToken->toString();
```

## Verify a Firebase Token

If a Firebase client app communicates with your server, you might need to identify the currently signed-in user. To do so, verify the integrity and authenticity of the ID token and retrieve the uid from it. You can use the uid transmitted in this way to securely identify the currently signed-in user on your server.

Use `Auth::verifyIdToken()` to verify an ID token:

```php
use Firebase\Auth\Token\Exception\InvalidToken;

$idTokenString = '...';

try {
    $verifiedIdToken = $auth->verifyIdToken($idTokenString);
} catch (InvalidToken $e) {
    echo 'The token is invalid: '.$e->getMessage();
} catch (\InvalidArgumentException $e) {
    echo 'The token could not be parsed: '.$e->getMessage();
}

// if you're using lcobucci/jwt ^4.0
$uid = $verifiedIdToken->claims()->get('sub');
// or, if you're using lcobucci/jwt ^3.0
$uid = $verifiedIdToken->claims()->get('sub');

$user = $auth->getUser($uid);
```

## Custom Authentication Flows

```php
use Kreait\Firebase\Auth;

// $signInResult = $auth->signIn*()

$signInResult->idToken(); // string|null
$signInResult->firebaseUserId(); // string|null
$signInResult->accessToken(); // string|null
$signInResult->refreshToken(); // string|null
$signInResult->data(); // array
$signInResult->asTokenResponse(); // array
```

`SignInResult::data()` returns the full payload of the response returned by the Firebase API.
`SignInResult::asTokenResponse()` returns the Sign-In result in a format that can be returned to clients:

*Note: Not all sign-in methods return all types of tokens.*

## Sign In Options

### Anonymous Sign In

```php
$signInResult = $auth->signInAnonymously();
```

*This method will create a new user in the Firebase Auth User Database each time it is invoked.*

### Sign In with Email and Password

```php
$signInResult = $auth->signInWithEmailAndPassword($email, $clearTextPassword);
```

### Sign In with Email and Oob Code

```php
$signInResult = $auth->signInWithEmailAndOobCode($email, $oobCode);
```

### Sign In with a Custom Token

```php
$signInResult = $auth->signInWithCustomToken($customToken);
```

### Sign In with a Refresh Token

```php
$signInResult = $auth->signInWithRefreshToken($refreshToken);
```

### Sign In without a token

```php
$signInResult = $auth->signInAsUser($userOrUid, array $claims = null);
```

## Invalidate User Session (Revoke Refresh Tokens)

This will revoke all sessions for a specified user and disable any new ID tokens for existing sessions from getting minted. Existing ID tokens may remain active until their natural expiration (one hour). To verify that ID tokens are revoked, use `Auth::verifyIdToken()` with the second parameter set to `true`.

If the check fails, a `RevokedIdToken` exception will be thrown.

```php
use Kreait\Firebase\Exception\Auth\RevokedIdToken;

$auth->revokeRefreshTokens($uid);

try {
    $verifiedIdToken = $auth->verifyIdToken($idTokenString, $checkIfRevoked = true);
} catch (RevokedIdToken $e) {
    echo $e->getMessage();
}
```

## Create a session cookie

```php
use Kreait\Firebase\Auth\CreateSessionCookie\FailedToCreateSessionCookie;

$idToken = '...';

// The TTL must be between 5 minutes and 2 weeks and can be provided as
// an integer value in seconds or a DateInterval

$fiveMinutes = 300;
$oneWeek = new \DateInterval('P7D');

try {
    $sessionCookieString = $auth->createSessionCookie($idToken, $oneWeek);
} catch (FailedToCreateSessionCookie $e) {
    echo $e->getMessage();
}
```
