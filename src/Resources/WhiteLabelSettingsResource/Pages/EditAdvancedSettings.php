<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;
use Illuminate\Database\Eloquent\Model;

class EditAdvancedSettings extends EditRecord
{
    protected static string $resource = WhiteLabelSettingsResource::class;

    protected static ?string $title = 'Advanced';

    protected static ?string $navigationLabel = 'Advanced';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['metadata'] = array_merge(
            $this->record->metadata ?? [],
            $data['metadata'] ?? [],
        );

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);
        $this->js('setTimeout(() => window.location.reload(), 250)');

        return $record;
    }

    public function mount(int|string|null $record = null): void
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
                        ->default(config('filament-white-label.defaults.unsaved_changes_alerts', false))
                        ->helperText('Warn before leaving pages with unsaved changes.'),

                    Toggle::make('metadata.spa_mode')
                        ->label('SPA Mode')
                        ->default(config('filament-white-label.defaults.spa_mode', false))
                        ->helperText('Single-page application mode for faster navigation.'),
                ])->columns(2),

                Section::make('Notifications')->schema([
                    Toggle::make('metadata.database_notifications')
                        ->label('Database Notifications')
                        ->default(config('filament-white-label.defaults.database_notifications', false))
                        ->helperText('Enable database notifications in the topbar/sidebar.'),

                    Select::make('metadata.database_notifications_polling')
                        ->label('Polling Interval')
                        ->default(config('filament-white-label.defaults.database_notifications_polling', '30s'))
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

                Section::make('Styling')->schema([
                    Select::make('metadata.font_scale')
                        ->label('Font Scale')
                        ->default(null)
                        ->options([
                            null => 'Default (100%)',
                            '90%' => '90% (Compact)',
                            '100%' => '100% (Default)',
                            '110%' => '110% (Large)',
                            '120%' => '120% (Extra Large)',
                        ])
                        ->helperText('Global font size multiplier for accessibility or density.'),

                    Select::make('metadata.form_density')
                        ->label('Form Density')
                        ->default('default')
                        ->options([
                            'default' => 'Default',
                            'compact' => 'Compact',
                            'spacious' => 'Spacious',
                        ])
                        ->helperText('Padding and spacing within form sections and fields.'),

                    Select::make('metadata.table_row_density')
                        ->label('Table Row Density')
                        ->default('default')
                        ->options([
                            'default' => 'Default',
                            'compact' => 'Compact',
                            'spacious' => 'Spacious',
                        ])
                        ->helperText('Vertical padding of table rows.'),

                    Select::make('metadata.modal_size')
                        ->label('Default Modal Size')
                        ->default('default')
                        ->options([
                            'default' => 'Default',
                            'small' => 'Small (480px)',
                            'medium' => 'Medium (640px)',
                            'large' => 'Large (800px)',
                            'extra-large' => 'Extra Large (1024px)',
                        ])
                        ->helperText('Default max-width for modal dialogs.'),

                    Select::make('metadata.transition_speed')
                        ->label('Transition Speed')
                        ->default('default')
                        ->options([
                            'default' => 'Default',
                            'none' => 'None',
                            'fast' => 'Fast',
                            'slow' => 'Slow',
                        ])
                        ->helperText('Duration of CSS transitions on buttons, dropdowns, modals, and sidebar.'),
                ])->columns(2),
            ]);
    }
}
