<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Commands;

use FilamentWhiteLabel\Services\WhiteLabel;
use Illuminate\Console\Command;

class ClearWhiteLabelCacheCommand extends Command
{
    protected $signature = 'white-label:clear-cache
                            {--tenant= : Clear cache for a specific tenant ID}
                            {--panel= : Clear cache for a specific panel ID}';

    protected $description = 'Clear the white-label brand settings cache';

    public function handle(): int
    {
        $tenantId = $this->option('tenant');
        $panelId = $this->option('panel');

        WhiteLabel::clearCache(null, $panelId);

        $this->info('White-label cache cleared.');

        return self::SUCCESS;
    }
}