<?php
namespace App\Notifications\Providers;

use App\Notifications\Contracts\NotificationStrategy;
use SendGrid;
use Exception;

class SendGridEmailProvider implements NotificationStrategy
{
    protected $sendGrid;

    public function __construct()
    {
        $this->sendGrid = new SendGrid(env('SENDGRID_API_KEY'));
    }

    public function send(string $to, string $subject, string $body = null): bool
    {
        if (env('MOCK_MODE', false)) {
            \Log::info("Mock send email to $to with subject $subject and body $body using SendGrid");
            return true;
        }

        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("your@example.com", "Example User");
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/plain", $body);

        try {
            $response = $this->sendGrid->send($email);
            return $response->statusCode() == 202;
        } catch (Exception $e) {
            // Handle exception
            return false;
        }
    }

    public function getStatus(string $trackingId): string
    {
        // Replace with actual logic to get the status from SendGrid
        $status = 'sent';
        return $status;
    }
}
