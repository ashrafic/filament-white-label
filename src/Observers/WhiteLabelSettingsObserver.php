<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Observers;

use FilamentWhiteLabel\Models\WhiteLabelSettings;
use FilamentWhiteLabel\Services\WhiteLabel;

class WhiteLabelSettingsObserver
{
    public function saved(WhiteLabelSettings $whiteLabelSettings): void
    {
        WhiteLabel::clearCache($whiteLabelSettings->tenant, $whiteLabelSettings->panel_id);
    }

    public function deleted(WhiteLabelSettings $whiteLabelSettings): void
    {
        WhiteLabel::clearCache($whiteLabelSettings->tenant, $whiteLabelSettings->panel_id);
    }
}
