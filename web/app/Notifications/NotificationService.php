<?php
namespace App\Notifications;

use App\Jobs\UpdateNotificationStatus;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\Providers\PlivoSmsProvider;
use App\Notifications\Providers\SendGridEmailProvider;
use App\Notifications\Providers\SesEmailProvider;
use App\Notifications\Providers\TwilioSmsProvider;
use Illuminate\Support\Facades\Config;


class NotificationService
{
    protected $smsProviders = [
        'plivo' => PlivoSmsProvider::class,
        'twilio' => TwilioSmsProvider::class,
    ];

    protected $emailProviders = [
        'ses' => SesEmailProvider::class,
        'sendgrid' => SendGridEmailProvider::class,
    ];

    public function sendNotification(User $user, string $message, $type = null): bool
    {
        if ($type !== null && in_array($type, ['sms', 'email'])) {
            return $this->sendWithUserPreference($user, $message, $type);
        } else {
            $providerType = Config::get('notification.' . $type . '_provider');

            if ($type === 'sms') {
                return $this->sendWithProvider($user->phone, $message, $providerType);
            } elseif ($type === 'email') {
                return $this->sendWithProvider($user->email, $message, $providerType);
            }
        }

        return false;
    }

    protected function sendWithUserPreference(User $user, string $message, string $type): bool
    {
        $preference = $user->preference->notification_preference ?? null;

        $recipient = $providerType = null;
        if ($preference === 'email') {
            $recipient = $user->email;
            $providerType = Config::get('notification.email_provider');
        } elseif ($preference === 'sms') {
            $recipient = $user->phone;
            $providerType = Config::get('notification.sms_provider');
        }

        $trackingId = $this->sendWithProvider($recipient, $message, $providerType);

        if ($trackingId !== null) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $preference,
                'message' => $message,
                'status' => 'pending',
                'provider' => $providerType,
                'tracking_id' => $trackingId,
            ]);
        }
        UpdateNotificationStatus::dispatch($trackingId)->delay(now()->addMinutes(2));

        return $trackingId;
    }

    protected function sendWithProvider(string $recipient, string $message, string $provider): ?string
    {
        $trackingId = null;

        if ($provider === 'plivo' || $provider === 'twilio') {
            $smsProvider = app($this->smsProviders[$provider]);
            $trackingId = $smsProvider->send($recipient, $message);
        } elseif ($provider === 'ses' || $provider === 'sendgrid') {
            $emailProvider = app($this->emailProviders[$provider]);
            $trackingId = $emailProvider->send($recipient, $message);
        }

        return $trackingId;
    }
}
