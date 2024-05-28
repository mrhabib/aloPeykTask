<?php
namespace App\Notifications\Providers;

use App\Notifications\Contracts\NotificationStrategy;
use Plivo\RestClient;
use Exception;

class PlivoSmsProvider implements NotificationStrategy
{
    protected $plivo;

    public function __construct()
    {
        $this->plivo = new RestClient(env('PLIVO_AUTH_ID'), env('PLIVO_AUTH_TOKEN'));
    }

    public function send(string $to, string $message, string $body = null): bool
    {
        if (env('MOCK_MODE', false)) {
            \Log::info("Mock send SMS to $to with message $message using Plivo");
            return true;
        }

        try {
            $this->plivo->messages->create(
                env('PLIVO_FROM'),
                [$to],
                $message
            );
            return true;
        } catch (Exception $e) {
            // Handle exception
            return false;
        }
    }

    public function getStatus(string $trackingId): string
    {
        // Replace with actual logic to get the status from Plivo
        $status = 'sent';
        return $status;
    }
}
