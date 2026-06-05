<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;

class EditLayoutSettings extends EditRecord
{
    protected static string $resource = WhiteLabelSettingsResource::class;

    protected static ?string $title = 'Layout';
    protected static ?string $navigationLabel = 'Layout';

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
                Section::make('Navigation')->schema([
                    Toggle::make('metadata.topbar')
                        ->label('Top Bar')
                        ->helperText('Show the top bar. Disable for minimal header.'),

                    Toggle::make('metadata.top_navigation')
                        ->label('Top Navigation')
                        ->helperText('Use top navigation bar instead of sidebar.'),

                    Toggle::make('metadata.sidebar_collapsible_on_desktop')
                        ->label('Collapsible Sidebar')
                        ->helperText('Allows sidebar to collapse (icons only when collapsed).'),

                    Toggle::make('metadata.sidebar_fully_collapsible_on_desktop')
                        ->label('Fully Collapsible Sidebar')
                        ->helperText('Allows sidebar to hide completely. Requires Collapsible Sidebar to be ON.'),

                    Toggle::make('metadata.collapsible_navigation_groups')
                        ->label('Collapsible Navigation Groups')
                        ->helperText('Allow navigation groups to be collapsed.'),
                ])->columns(2),

                Section::make('Display')->schema([
                    Toggle::make('metadata.breadcrumbs')
                        ->label('Breadcrumbs')
                        ->helperText('Show breadcrumb navigation.'),
                ]),
            ]);
    }
}
