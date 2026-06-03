<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Commands;

use FilamentWhiteLabel\Services\BrandResolver;
use Illuminate\Console\Command;

class ClearWhiteLabelCacheCommand extends Command
{
    protected $signature = 'white-label:clear-cache
                            {--tenant= : Clear cache for a specific tenant ID}';

    protected $description = 'Clear the white-label brand settings cache';

    public function handle(): int
    {
        BrandResolver::clearCache();

        if ($this->option('tenant')) {
            $this->info("White-label cache cleared for tenant {$this->option('tenant')}.");
        } else {
            $this->info('All white-label cache cleared.');
        }

        return self::SUCCESS;
    }
}