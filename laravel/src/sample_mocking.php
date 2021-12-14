<?php

namespace Tests\Unit\Services;

use App\Services\FirebaseService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Auth\UserRecord;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Tests\TestCase;

class FirebaseServiceTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function createUserIsSuccessful(): void
    {
        $password = Str::random(8);
        $email = $this->faker->email;

        $mockUser = new UserRecord();
        $mockUser->email = $email;

        $this->mock(Auth::class, function ($mock) use ($mockUser) {
            $mock->shouldReceive('createUser')
                ->once()
                ->andReturn($mockUser);
        });

        $user = self::firebaseService()->createUser($email, $password);

        $this->assertNotEmpty($user);
        $this->assertSame($email, $user->email);
    }

    /**
     * @test
     */
    public function createUserFailedOnEmptyParam(): void
    {
        $this->expectException(\ArgumentCountError::class);
        self::firebaseService()->createUser();
    }

    /**
     * @test
     */
    public function sendPushNotificationToClientIsSuccessful(): void
    {
        // some codes here

        $this->mock(FirebaseService::class, function ($mock) use ($mockMessage) {
            $mock->shouldReceive('sendPushNotificationToClient')
                ->once()
                ->andReturn($mockMessage);
        });

        $result = self::firebaseService()->sendPushNotificationToClient(/** Code here */);
        $output = $result->jsonSerialize();

        // assertion here
    }

    private static function firebaseService()
    {
        return app()->make(FirebaseService::class);
    }
}
