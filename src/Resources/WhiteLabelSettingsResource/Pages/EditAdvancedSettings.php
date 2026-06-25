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

    public static function getTitle(): string
    {
        return __('filament-white-label::filament-white-label.resource.page.advanced.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-white-label::filament-white-label.resource.page.advanced.nav_label');
    }

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
                Section::make(__('filament-white-label::filament-white-label.resource.sections.behavior'))->schema([
                    Toggle::make('metadata.unsaved_changes_alerts')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.unsaved_changes.label'))
                        ->default(config('filament-white-label.defaults.unsaved_changes_alerts', false))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.unsaved_changes.helper_text')),

                    Toggle::make('metadata.spa_mode')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.spa_mode.label'))
                        ->default(config('filament-white-label.defaults.spa_mode', false))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.spa_mode.helper_text')),
                ])->columns(2),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.notifications'))->schema([
                    Toggle::make('metadata.database_notifications')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.database_notifications.label'))
                        ->default(config('filament-white-label.defaults.database_notifications', false))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.database_notifications.helper_text')),

                    Select::make('metadata.database_notifications_polling')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.polling_interval.label'))
                        ->default(config('filament-white-label.defaults.database_notifications_polling', '30s'))
                        ->options(fn () => [
                            null => __('filament-white-label::filament-white-label.resource.options.polling_interval.30s'),
                            '10s' => __('filament-white-label::filament-white-label.resource.options.polling_interval.10s'),
                            '30s' => __('filament-white-label::filament-white-label.resource.options.polling_interval.30s'),
                            '60s' => __('filament-white-label::filament-white-label.resource.options.polling_interval.60s'),
                            '2m' => __('filament-white-label::filament-white-label.resource.options.polling_interval.2m'),
                            '5m' => __('filament-white-label::filament-white-label.resource.options.polling_interval.5m'),
                        ])
                        ->visible(fn ($get) => $get('metadata.database_notifications')),
                ])->columns(2),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.styling'))->schema([
                    Select::make('metadata.font_scale')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.font_scale.label'))
                        ->default(null)
                        ->options(fn () => [
                            null => __('filament-white-label::filament-white-label.resource.options.default'),
                            '90%' => __('filament-white-label::filament-white-label.resource.options.font_scale.90'),
                            '100%' => __('filament-white-label::filament-white-label.resource.options.font_scale.100'),
                            '110%' => __('filament-white-label::filament-white-label.resource.options.font_scale.110'),
                            '120%' => __('filament-white-label::filament-white-label.resource.options.font_scale.120'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.font_scale.helper_text')),

                    Select::make('metadata.form_density')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.form_density.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'compact' => __('filament-white-label::filament-white-label.resource.options.compact'),
                            'spacious' => __('filament-white-label::filament-white-label.resource.options.spacious'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.form_density.helper_text')),

                    Select::make('metadata.table_row_density')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.table_row_density.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'compact' => __('filament-white-label::filament-white-label.resource.options.compact'),
                            'spacious' => __('filament-white-label::filament-white-label.resource.options.spacious'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.table_row_density.helper_text')),

                    Select::make('metadata.modal_size')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.modal_size.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'small' => __('filament-white-label::filament-white-label.resource.options.modal_size.small'),
                            'medium' => __('filament-white-label::filament-white-label.resource.options.modal_size.medium'),
                            'large' => __('filament-white-label::filament-white-label.resource.options.modal_size.large'),
                            'extra-large' => __('filament-white-label::filament-white-label.resource.options.modal_size.xl'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.modal_size.helper_text')),

                    Select::make('metadata.transition_speed')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.transition_speed.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'none' => __('filament-white-label::filament-white-label.resource.options.transition_speed.none'),
                            'fast' => __('filament-white-label::filament-white-label.resource.options.fast'),
                            'slow' => __('filament-white-label::filament-white-label.resource.options.slow'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.transition_speed.helper_text')),
                ])->columns(2),
            ]);
    }
}
