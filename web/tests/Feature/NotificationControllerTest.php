<?php
// tests/Feature/NotificationControllerTest.php

namespace Tests\Feature;

use App\Jobs\UpdateNotificationStatus;
use App\Notifications\NotificationService;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;


class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup any additional initialization for your tests here
    }

    public function testSendNotification()
    {
        Queue::fake();

        $user = User::factory()->create();
        $user->preference()->create(['notification_preference' => 'sms']);

        $notificationService = $this->mock(NotificationService::class, function ($mock) {
            $mock->shouldReceive('sendNotification')
                ->andReturn('mock-tracking-id');
        });

        $this->app->instance(NotificationService::class, $notificationService);

        $response = $this->postJson('/notifications/send', [
            'user_id' => $user->id,
            'message' => 'Test message',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        Queue::assertPushed(UpdateNotificationStatus::class, function ($job) {
            return $job->trackingId === 'mock-tracking-id';
        });

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'sms',
            'message' => 'Test message',
            'status' => 'pending',
            'provider' => config('notification.providers.sms'),
            'tracking_id' => 'mock-tracking-id',
        ]);
    }
}
