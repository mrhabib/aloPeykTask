<?php
namespace App\Notifications\Providers;

use App\Notifications\Contracts\NotificationStrategy;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class SesEmailProvider implements NotificationStrategy
{
    protected $sesClient;

    public function __construct()
    {
        $this->sesClient = new SesClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function send(string $to, string $subject, string $body = null): bool
    {
        if (env('MOCK_MODE', false)) {
            \Log::info("Mock send email to $to with subject $subject and body $body using SES");
            return true;
        }

        try {
            $result = $this->sesClient->sendEmail([
                'Destination' => [
                    'ToAddresses' => [$to],
                ],
                'Message' => [
                    'Body' => [
                        'Text' => [
                            'Charset' => 'UTF-8',
                            'Data' => $body,
                        ],
                    ],
                    'Subject' => [
                        'Charset' => 'UTF-8',
                        'Data' => $subject,
                    ],
                ],
                'Source' => 'your@example.com',
            ]);
            return $result['@metadata']['statusCode'] == 200;
        } catch (AwsException $e) {
            // Handle exception
            return false;
        }
    }

    public function getStatus(string $trackingId): string
    {
        // Replace with actual logic to get the status from Ses
        $status = 'sent';
        return $status;
    }
}
