<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallWhiteLabelCommand extends Command
{
    protected $signature = 'white-label:install';

    protected $description = 'Install Filament White-Label — publish config and migrations';

    public function handle(): int
    {
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║     Filament White-Label Installer       ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->newLine();

        $this->publishConfig();
        $this->publishMigrations();

        $this->newLine();
        $this->info('Filament White-Label installed successfully.');
        $this->newLine();
        $this->line('Next steps:');
        $this->line('  1. Review the published migration in <info>database/migrations/</info>');
        $this->line('  2. Run <info>php artisan migrate</info>');
        $this->line('  3. Review the config at <info>config/filament-white-label.php</info>');
        $this->line('  4. Add traits to your Tenant model and PanelProvider');
        $this->newLine();
        $this->line('📖 Documentation: <comment>https://github.com/ashrafic/filament-white-label</comment>');

        return self::SUCCESS;
    }

    protected function publishConfig(): void
    {
        $configPath = config_path('filament-white-label.php');

        if (File::exists($configPath)) {
            $this->line('  ⏭  Config already published — skipping.');

            return;
        }

        $this->call('vendor:publish', [
            '--tag' => 'filament-white-label-config',
        ]);

        $this->line('  ✅ Config published to <info>config/filament-white-label.php</info>');
    }

    protected function publishMigrations(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'filament-white-label-migrations',
        ]);

        $this->line('  ✅ Migration published to <info>database/migrations/</info>');
    }
}
