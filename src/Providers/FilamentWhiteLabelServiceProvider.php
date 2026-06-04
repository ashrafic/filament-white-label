<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Providers;

use Filament\Panel;
use FilamentWhiteLabel\Commands\ClearWhiteLabelCacheCommand;
use FilamentWhiteLabel\Commands\InstallWhiteLabelCommand;
use FilamentWhiteLabel\Listeners\ApplyTenantEmailBranding;
use FilamentWhiteLabel\Models\BrandSettings;
use FilamentWhiteLabel\Observers\BrandSettingsObserver;
use FilamentWhiteLabel\Services\BrandResolver;
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

        Panel::macro('whiteLabel', function (bool $condition = true): Panel {
            if (! $condition || ! config('filament-white-label.enabled', true)) {
                return $this;
            }

            return $this
                ->brandName(fn (): ?string => BrandResolver::brandName())
                ->brandLogo(fn (): ?string => BrandResolver::logoUrl())
                ->favicon(fn (): ?string => BrandResolver::faviconUrl())
                ->colors(fn (): array => BrandResolver::colors())
                ->font(fn (): ?string => BrandResolver::fontFamily())
                ->renderHook('panels::head.start', fn (): string => BrandResolver::fontLinkTag() . BrandResolver::customCssTag());
        });
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