<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\BrandSettingsResource\Pages;

use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Resources\BrandSettingsResource;

class EditLayoutSettings extends EditRecord
{
    protected static string $resource = BrandSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int | string | null $record = null): void
    {
        $this->record = BrandSettingsResource::resolveBrandSettingsRecord();

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
                    Toggle::make('metadata.top_navigation')
                        ->label('Top Navigation')
                        ->helperText('Use top navigation bar instead of sidebar.'),

                    Toggle::make('metadata.sidebar_collapsible_on_desktop')
                        ->label('Collapsible Sidebar')
                        ->helperText('Allow sidebar to collapse on desktop.'),

                    Toggle::make('metadata.sidebar_fully_collapsible_on_desktop')
                        ->label('Fully Collapsible Sidebar')
                        ->helperText('Allow sidebar to fully collapse (icons only).'),

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
