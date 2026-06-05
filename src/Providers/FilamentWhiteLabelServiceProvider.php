<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Providers;

use Filament\Panel;
use FilamentWhiteLabel\Commands\ClearWhiteLabelCacheCommand;
use FilamentWhiteLabel\Commands\InstallWhiteLabelCommand;
use FilamentWhiteLabel\Listeners\ApplyTenantEmailBranding;
use FilamentWhiteLabel\Models\WhiteLabelSettings;
use FilamentWhiteLabel\Observers\WhiteLabelSettingsObserver;
use FilamentWhiteLabel\Services\WhiteLabel;
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
            WhiteLabelSettings::observe(WhiteLabelSettingsObserver::class);
        }

        Panel::macro('whiteLabel', function (bool $condition = true): Panel {
            if (! $condition || ! config('filament-white-label.enabled', true)) {
                return $this;
            }

            return $this
                ->brandName(fn (): ?string => WhiteLabel::brandName())
                ->brandLogo(fn (): ?string => WhiteLabel::logoUrl())
                ->brandLogoHeight(fn (): ?string => WhiteLabel::brandLogoHeight())
                ->darkModeBrandLogo(fn (): ?string => WhiteLabel::darkModeBrandLogoUrl())
                ->favicon(fn (): ?string => WhiteLabel::faviconUrl())
                ->colors(fn (): array => WhiteLabel::colors())
                ->font(fn (): ?string => WhiteLabel::fontFamily())
                ->topNavigation(fn (): bool => WhiteLabel::topNavigation())
                ->sidebarCollapsibleOnDesktop(fn (): bool => WhiteLabel::sidebarCollapsibleOnDesktop())
                ->sidebarFullyCollapsibleOnDesktop(fn (): bool => WhiteLabel::sidebarFullyCollapsibleOnDesktop())
                ->collapsibleNavigationGroups(fn (): bool => WhiteLabel::collapsibleNavigationGroups())
                ->breadcrumbs(fn (): bool => WhiteLabel::breadcrumbs())
                ->unsavedChangesAlerts(fn (): bool => WhiteLabel::unsavedChangesAlerts())
                ->spa(fn (): bool => WhiteLabel::spaMode())
                ->databaseNotifications(fn (): bool => WhiteLabel::databaseNotifications())
                ->databaseNotificationsPolling(fn (): ?string => WhiteLabel::databaseNotificationsPolling())
                ->renderHook('panels::head.start', fn (): string => WhiteLabel::fontLinkTag() . WhiteLabel::customCssTag());
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
