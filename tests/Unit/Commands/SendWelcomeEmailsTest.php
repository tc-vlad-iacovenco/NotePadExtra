<?php
namespace Tests\Unit\Commands;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SendWelcomeEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    #[Test] public function it_sends_welcome_email_to_verified_users_after_five_minutes()
    {
        // Create a user that was verified 6 minutes ago
        $verifiedUser = User::factory()->create([
            'email_verified_at' => now()->subMinutes(6),
            'welcome_email_sent_at' => null
        ]);

        // Create a user that was verified 4 minutes ago (shouldn't receive email yet)
        $recentUser = User::factory()->create([
            'email_verified_at' => now()->subMinutes(4),
            'welcome_email_sent_at' => null
        ]);

        // Create a user that already received the welcome email
        $welcomedUser = User::factory()->create([
            'email_verified_at' => now()->subMinutes(10),
            'welcome_email_sent_at' => now()->subMinutes(5)
        ]);

        // Run the command
        $this->artisan('emails:send-welcome')
            ->assertSuccessful();

        // Assert the correct user received the notification
        Notification::assertSentTo(
            $verifiedUser,
            WelcomeNotification::class
        );

        // Assert other users didn't receive the notification
        Notification::assertNotSentTo(
            [$recentUser, $welcomedUser],
            WelcomeNotification::class
        );

        // Assert the database was updated
        $this->assertNotNull($verifiedUser->fresh()->welcome_email_sent_at);
        $this->assertNull($recentUser->fresh()->welcome_email_sent_at);
    }

    #[Test] public function it_handles_no_eligible_users()
    {
        // Create users that aren't eligible
        User::factory()->create([
            'email_verified_at' => now()->subMinutes(4),
            'welcome_email_sent_at' => null
        ]);

        User::factory()->create([
            'email_verified_at' => now()->subMinutes(10),
            'welcome_email_sent_at' => now()
        ]);

        $this->artisan('emails:send-welcome')
            ->assertSuccessful();

        Notification::assertNothingSent();
    }

    #[Test] public function it_handles_unverified_users()
    {
        // Create an unverified user
        $unverifiedUser = User::factory()->create([
            'email_verified_at' => null,
            'welcome_email_sent_at' => null
        ]);

        $this->artisan('emails:send-welcome')
            ->assertSuccessful();

        Notification::assertNotSentTo(
            $unverifiedUser,
            WelcomeNotification::class
        );
    }

    #[Test] public function it_processes_multiple_eligible_users()
    {
        // Create multiple eligible users
        $users = User::factory(3)->create([
            'email_verified_at' => now()->subMinutes(6),
            'welcome_email_sent_at' => null
        ]);

        $this->artisan('emails:send-welcome')
            ->assertSuccessful();

        Notification::assertSentTo(
            $users,
            WelcomeNotification::class
        );

        // Assert all users were updated in the database
        $users->each(function ($user) {
            $this->assertNotNull($user->fresh()->welcome_email_sent_at);
        });
    }
}
