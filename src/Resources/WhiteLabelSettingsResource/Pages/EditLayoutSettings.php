<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;
use Illuminate\Database\Eloquent\Model;

class EditLayoutSettings extends EditRecord
{
    protected static string $resource = WhiteLabelSettingsResource::class;

    protected static ?string $title = 'Layout';

    protected static ?string $navigationLabel = 'Layout';

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
                Section::make('Navigation')->schema([
                    Toggle::make('metadata.topbar')
                        ->label('Top Bar')
                        ->default(config('filament-white-label.defaults.topbar', true))
                        ->helperText('Show the top bar with user menu and notifications.'),

                    Toggle::make('metadata.top_navigation')
                        ->label('Top Navigation')
                        ->live()
                        ->default(config('filament-white-label.defaults.top_navigation', false))
                        ->helperText('Move navigation from sidebar to top bar. Disables sidebar.'),
                ])->columns(2),

                Section::make('Sidebar')
                    ->schema([
                        Toggle::make('metadata.sidebar_collapsible_on_desktop')
                            ->label('Collapsible Sidebar')
                            ->disabled(fn ($get) => (bool) $get('metadata.top_navigation'))
                            ->default(config('filament-white-label.defaults.sidebar_collapsible_on_desktop', false))
                            ->helperText('Allows sidebar to collapse to icons only.'),

                        Toggle::make('metadata.sidebar_fully_collapsible_on_desktop')
                            ->label('Fully Collapsible Sidebar')
                            ->disabled(fn ($get) => (bool) $get('metadata.top_navigation'))
                            ->default(config('filament-white-label.defaults.sidebar_fully_collapsible_on_desktop', false))
                            ->helperText('Allows sidebar to hide completely.'),

                        Toggle::make('metadata.collapsible_navigation_groups')
                            ->label('Collapsible Navigation Groups')
                            ->disabled(fn ($get) => (bool) $get('metadata.top_navigation'))
                            ->default(config('filament-white-label.defaults.collapsible_navigation_groups', true))
                            ->helperText('Allow navigation groups to be expanded/collapsed.'),
                    ])->columns(2),

                Section::make('Display')->schema([
                    Toggle::make('metadata.breadcrumbs')
                        ->label('Breadcrumbs')
                        ->default(config('filament-white-label.defaults.breadcrumbs', true))
                        ->helperText('Show breadcrumb navigation.'),
                ]),
            ]);
    }
}
