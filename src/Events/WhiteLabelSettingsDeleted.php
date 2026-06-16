<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Events;

use FilamentWhiteLabel\Models\WhiteLabelSettings;
use Illuminate\Foundation\Events\Dispatchable;

class WhiteLabelSettingsDeleted
{
    use Dispatchable;

    public function __construct(
        public WhiteLabelSettings $settings,
    ) {}
}
