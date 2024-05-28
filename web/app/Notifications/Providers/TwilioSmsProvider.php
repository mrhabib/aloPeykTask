<?php
namespace App\Notifications\Providers;

use App\Notifications\Contracts\NotificationStrategy;
use Twilio\Rest\Client;
use Exception;

class TwilioSmsProvider implements NotificationStrategy
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function send(string $to, string $message, string $body = null): bool
    {
        if (env('MOCK_MODE', false)) {
            \Log::info("Mock send SMS to $to with message $message using Twilio");
            return true;
        }

        try {
            $this->twilio->messages->create($to, [
                'from' => env('TWILIO_FROM'),
                'body' => $message,
            ]);
            return true;
        } catch (Exception $e) {
            // Handle exception
            return false;
        }
    }

    public function getStatus(string $trackingId): string
    {
        // Replace with actual logic to get the status from Twilio
        $status = 'sent';
        return $status;
    }
}
