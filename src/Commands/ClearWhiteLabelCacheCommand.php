<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Commands;

use FilamentWhiteLabel\Services\WhiteLabel;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class ClearWhiteLabelCacheCommand extends Command
{
    protected $signature = 'white-label:clear-cache';

    protected function configure(): void
    {
        parent::configure();

        $this->setDescription((string) __('filament-white-label::filament-white-label.commands.clear_cache.description'));

        $this->getDefinition()->addOption(new InputOption(
            'tenant',
            null,
            InputOption::VALUE_OPTIONAL,
            (string) __('filament-white-label::filament-white-label.commands.clear_cache.option_tenant'),
        ));

        $this->getDefinition()->addOption(new InputOption(
            'panel',
            null,
            InputOption::VALUE_OPTIONAL,
            (string) __('filament-white-label::filament-white-label.commands.clear_cache.option_panel'),
        ));
    }

    public function handle(): int
    {
        $tenantId = $this->option('tenant');
        $panelId = $this->option('panel');

        WhiteLabel::clearCache(null, $panelId);

        $this->info((string) __('filament-white-label::filament-white-label.commands.clear_cache.success'));

        return self::SUCCESS;
    }
}
