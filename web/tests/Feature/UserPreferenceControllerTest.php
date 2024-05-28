<?php

// tests/Feature/UserPreferenceControllerTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class UserPreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup any additional initialization for your tests here
    }

    public function testShowUserPreference()
    {
        $user = User::factory()->create();
        $user->preference()->create(['notification_preference' => 'email']);

        $this->actingAs($user);

        $response = $this->getJson('/user/preference');

        $response->assertStatus(200);
        $response->assertJson(['notification_preference' => 'email']);
    }

    public function testUpdateUserPreference()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/user/preference', [
            'notification_preference' => 'sms',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['notification_preference' => 'sms']);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'notification_preference' => 'sms',
        ]);
    }

    // Additional test methods as needed
}
