<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Listeners;

use FilamentWhiteLabel\Events\WhiteLabelSettingsDeleted;
use FilamentWhiteLabel\Events\WhiteLabelSettingsSaved;
use FilamentWhiteLabel\Services\WhiteLabel;

class ClearWhiteLabelCache
{
    public function handle(WhiteLabelSettingsSaved|WhiteLabelSettingsDeleted $event): void
    {
        WhiteLabel::clearCache(
            $event->settings->tenant,
            $event->settings->panel_id,
        );
    }
}
