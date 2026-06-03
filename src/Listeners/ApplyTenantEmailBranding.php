<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Listeners;

use FilamentWhiteLabel\Services\BrandResolver;
use Illuminate\Mail\Events\MessageSending;

class ApplyTenantEmailBranding
{
    public function handle(MessageSending $event): void
    {
        if (! config('filament-white-label.email.enabled', true)) {
            return;
        }

        if (! config('filament-white-label.enabled', true)) {
            return;
        }

        $settings = BrandResolver::resolve();

        if (! $settings) {
            return;
        }

        $message = $event->message;

        if ($settings->email_from_address) {
            $message->from(
                $settings->email_from_address,
                $settings->email_from_name ?: $settings->brand_name ?: null
            );
        }
    }
}