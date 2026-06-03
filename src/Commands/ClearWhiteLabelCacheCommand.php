<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Commands;

use FilamentWhiteLabel\Services\BrandResolver;
use Illuminate\Console\Command;

class ClearWhiteLabelCacheCommand extends Command
{
    protected $signature = 'white-label:clear-cache';

    protected $description = 'Clear the white-label brand settings cache';

    public function handle(): int
    {
        BrandResolver::clearCache();

        $this->info('White-label cache cleared.');

        return self::SUCCESS;
    }
}