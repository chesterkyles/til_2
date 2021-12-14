<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Auth::viaRequest('firebase', function (HttpRequest $request) {
            return $this->checkFirebaseUser($request->bearerToken());
        });

        Gate::define('vendor', function($user) {
            // some condition here to return true
            return false;
        });

        Passport::routes();
    }

    private function checkFirebaseUser(?string $token)
    {
        $auth = app('firebase.auth');

        try {
            $verifiedIdToken = $auth->verifyIdToken($token);
        } catch (InvalidToken $e){
            \Log::error($e->getMessage());
            return null;
        } catch (\InvalidArgumentException $e) {
            \Log::error($e->getMessage());
            return null;
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        return User::where('firebase_uid', $uid)->first();
    }
}
