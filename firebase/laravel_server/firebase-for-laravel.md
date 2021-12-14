# Firebase For Laravel

Link: <https://github.com/kreait/laravel-firebase>

## Installation

The above package requires Laravel 6.x and higher.

```sh
composer require kreait/laravel-firebase
```

Add the following service provider in `config/app.php`

```php
<?php

return [
    // ...
    'providers' => [
        // ...
        Kreait\Laravel\Firebase\ServiceProvider::class
    ]
    // ...
];
```

## Configuration

1. Generate a Service Account in your [Firebase](https://firebase.google.com/) project if you haven't done it yet
2. Download the Service Account JSON file
3. Specify environment variable starting with `FIREBASE_` in `.env` file. For example:

    ```sh
    # relative or full path to the Service Account JSON file
    FIREBASE_CREDENTIALS=
    # You can find the database URL for your project at
    # https://console.firebase.google.com/project/_/database
    FIREBASE_DATABASE_URL=https://<your-project>.firebaseio.com

    ```

4. Run the following command for further configuration in `config/firebase.php`

    ```sh
    php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider" --tag=config
    ```

## Usage

Component | Automatic Injection | Facades | `app()`
-------- | -------------------- | ------- | --------
[Authentication](https://firebase-php.readthedocs.io/en/stable/authentication.html) | `\Kreait\Firebase\Auth` | `Firebase::auth()` | `app('firebase.auth')`
[Cloud Firestore](https://firebase-php.readthedocs.io/en/stable/cloud-firestore.html) | `\Kreait\Firebase\Firestore` | `Firebase::firestore()` | `app('firebase.firestore')`
[Cloud Messaging (FCM)](https://firebase-php.readthedocs.io/en/stable/cloud-messaging.html) | `\Kreait\Firebase\Messaging` | `Firebase::messaging()` | `app('firebase.messaging')`
[Dynamic Links](https://firebase-php.readthedocs.io/en/stable/dynamic-links.html) | `\Kreait\Firebase\DynamicLinks` | `Firebase::dynamicLinks()` | `app('firebase.dynamic_links')`
[Realtime Database](https://firebase-php.readthedocs.io/en/stable/realtime-database.html) | `\Kreait\Firebase\Database` | `Firebase::database()` | `app('firebase.database')`
[Remote Config](https://firebase-php.readthedocs.io/en/stable/remote-config.html) | `\Kreait\Firebase\RemoteConfig` | `Firebase::remoteConfig()` | `app('firebase.remote_config')`
[Cloud Storage](https://firebase-php.readthedocs.io/en/stable/cloud-storage.html) | `\Kreait\Firebase\Storage` | `Firebase::storage()` | `app('firebase.storage')`

Read more here: <https://github.com/kreait/laravel-firebase>
