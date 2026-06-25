<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallWhiteLabelCommand extends Command
{
    protected $signature = 'white-label:install';

    public function __construct()
    {
        parent::__construct();

        $this->setDescription((string) __('filament-white-label::commands.install.description'));
    }

    public function handle(): int
    {
        $banner = (string) __('filament-white-label::commands.install.banner');

        $this->info(str_repeat('╔', 46));
        $this->info('║     '.str_pad($banner, 34).'║');
        $this->info(str_repeat('╚', 46));
        $this->newLine();

        $this->publishConfig();
        $this->publishMigrations();

        $this->newLine();
        $this->info((string) __('filament-white-label::commands.install.success'));
        $this->newLine();
        $this->line((string) __('filament-white-label::commands.install.next_steps'));
        $this->line('  '.__('filament-white-label::commands.install.step_1'));
        $this->line('  '.__('filament-white-label::commands.install.step_2'));
        $this->line('  '.__('filament-white-label::commands.install.step_3'));
        $this->line('  '.__('filament-white-label::commands.install.step_4'));
        $this->newLine();
        $this->line((string) __('filament-white-label::commands.install.docs'));

        return self::SUCCESS;
    }

    protected function publishConfig(): void
    {
        $configPath = config_path('filament-white-label.php');

        if (File::exists($configPath)) {
            $this->line('  '.__('filament-white-label::commands.install.config_skipped'));

            return;
        }

        $this->call('vendor:publish', [
            '--tag' => 'filament-white-label-config',
        ]);

        $this->line('  '.__('filament-white-label::commands.install.config_published'));
    }

    protected function publishMigrations(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'filament-white-label-migrations',
        ]);

        $this->line('  '.__('filament-white-label::commands.install.migration_published'));
    }
}
