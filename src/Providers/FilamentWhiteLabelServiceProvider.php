<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Providers;

use FilamentWhiteLabel\Commands\ClearWhiteLabelCacheCommand;
use FilamentWhiteLabel\Commands\InstallWhiteLabelCommand;
use FilamentWhiteLabel\Listeners\ApplyTenantEmailBranding;
use FilamentWhiteLabel\Models\BrandSettings;
use FilamentWhiteLabel\Observers\BrandSettingsObserver;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class FilamentWhiteLabelServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/filament-white-label.php' => config_path('filament-white-label.php'),
        ], 'filament-white-label-config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'filament-white-label-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearWhiteLabelCacheCommand::class,
                InstallWhiteLabelCommand::class,
            ]);
        }

        if (config('filament-white-label.enabled', true)) {
            BrandSettings::observe(BrandSettingsObserver::class);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/filament-white-label.php',
            'filament-white-label'
        );

        if (config('filament-white-label.email.enabled', true)) {
            Event::listen(
                MessageSending::class,
                ApplyTenantEmailBranding::class
            );
        }
    }
}