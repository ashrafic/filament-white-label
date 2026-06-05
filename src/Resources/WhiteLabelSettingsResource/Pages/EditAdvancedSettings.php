<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;

class EditAdvancedSettings extends EditRecord
{
    protected static string $resource = WhiteLabelSettingsResource::class;

    protected static ?string $title = 'Advanced';
    protected static ?string $navigationLabel = 'Advanced';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): ?string
    {
        return request()->url();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['metadata'] = array_merge(
            $this->record->metadata ?? [],
            $data['metadata'] ?? [],
        );

        return $data;
    }

    public function mount(int | string | null $record = null): void
    {
        $this->record = WhiteLabelSettingsResource::resolveRecord();

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                Section::make('Behavior')->schema([
                    Toggle::make('metadata.unsaved_changes_alerts')
                        ->label('Unsaved Changes Alerts')
                        ->helperText('Warn before leaving pages with unsaved changes.'),

                    Toggle::make('metadata.spa_mode')
                        ->label('SPA Mode')
                        ->helperText('Single-page application mode for faster navigation.'),
                ])->columns(2),

                Section::make('Notifications')->schema([
                    Toggle::make('metadata.database_notifications')
                        ->label('Database Notifications')
                        ->helperText('Enable database notifications in the topbar/sidebar.'),

                    Select::make('metadata.database_notifications_polling')
                        ->label('Polling Interval')
                        ->options([
                            null => 'Default (30s)',
                            '10s' => '10 seconds',
                            '30s' => '30 seconds',
                            '60s' => '1 minute',
                            '2m' => '2 minutes',
                            '5m' => '5 minutes',
                        ])
                        ->visible(fn ($get) => $get('metadata.database_notifications')),
                ])->columns(2),
            ]);
    }
}
