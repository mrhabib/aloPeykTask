<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Notifications\Providers\PlivoSmsProvider;
use App\Notifications\Providers\SendGridEmailProvider;
use App\Notifications\Providers\SesEmailProvider;
use App\Notifications\Providers\TwilioSmsProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateNotificationStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $trackingId;

    /**
     * Create a new job instance.
     */
    public function __construct( string $trackingId )
    {
        $this->trackingId = $trackingId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notification = Notification::where('tracking_id', $this->trackingId)->first();

        if ($notification) {
            // Get status from provider and update the notification status
            // each provider have details or tracking method . get it and update status.
            $provider = app($this->getProviderClass($notification->provider));
            $status = $provider->getStatus($this->trackingId);

            $notification->update(['status' => $status]);
        }
    }
    protected function getProviderClass(string $provider): string
    {
        $providers = [
            'twilio'   => TwilioSmsProvider::class,
            'plivo'    => PlivoSmsProvider::class,
            'ses'      => SesEmailProvider::class,
            'sendgrid' => SendGridEmailProvider::class,
        ];

        return $providers[$provider];
    }
}
