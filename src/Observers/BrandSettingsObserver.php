<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Observers;

use FilamentWhiteLabel\Models\BrandSettings;
use FilamentWhiteLabel\Services\BrandResolver;

class BrandSettingsObserver
{
    public function saved(BrandSettings $brandSettings): void
    {
        BrandResolver::clearCache($brandSettings->tenant, $brandSettings->panel_id);
    }

    public function deleted(BrandSettings $brandSettings): void
    {
        BrandResolver::clearCache($brandSettings->tenant, $brandSettings->panel_id);
    }
}