<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;
use Illuminate\Database\Eloquent\Model;

class EditLayoutSettings extends EditRecord
{
    protected static string $resource = WhiteLabelSettingsResource::class;

    public function getTitle(): string
    {
        return __('filament-white-label::filament-white-label.resource.page.layout.title');
    }

    public function getNavigationLabel(): string
    {
        return __('filament-white-label::filament-white-label.resource.page.layout.nav_label');
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
                Section::make(__('filament-white-label::filament-white-label.resource.sections.navigation'))->schema([
                    Toggle::make('metadata.topbar')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.topbar.label'))
                        ->default(config('filament-white-label.defaults.topbar', true))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.topbar.helper_text')),

                    Toggle::make('metadata.top_navigation')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.top_navigation.label'))
                        ->live()
                        ->default(config('filament-white-label.defaults.top_navigation', false))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.top_navigation.helper_text')),
                ])->columns(2),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.sidebar'))
                    ->schema([
                        Toggle::make('metadata.sidebar_collapsible_on_desktop')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.sidebar_collapsible.label'))
                            ->disabled(fn ($get) => (bool) $get('metadata.top_navigation'))
                            ->default(config('filament-white-label.defaults.sidebar_collapsible_on_desktop', false))
                            ->live()
                            ->afterStateUpdated(fn ($state, $set) => $state && $set('metadata.sidebar_fully_collapsible_on_desktop', false))
                            ->helperText(__('filament-white-label::filament-white-label.resource.fields.sidebar_collapsible.helper_text')),

                        Toggle::make('metadata.sidebar_fully_collapsible_on_desktop')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.sidebar_fully_collapsible.label'))
                            ->disabled(fn ($get) => (bool) $get('metadata.top_navigation'))
                            ->default(config('filament-white-label.defaults.sidebar_fully_collapsible_on_desktop', false))
                            ->live()
                            ->afterStateUpdated(fn ($state, $set) => $state && $set('metadata.sidebar_collapsible_on_desktop', false))
                            ->helperText(__('filament-white-label::filament-white-label.resource.fields.sidebar_fully_collapsible.helper_text')),

                        Toggle::make('metadata.collapsible_navigation_groups')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.collapsible_navigation_groups.label'))
                            ->disabled(fn ($get) => (bool) $get('metadata.top_navigation'))
                            ->default(config('filament-white-label.defaults.collapsible_navigation_groups', true))
                            ->helperText(__('filament-white-label::filament-white-label.resource.fields.collapsible_navigation_groups.helper_text')),
                    ])->columns(2),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.display'))->schema([
                    Toggle::make('metadata.breadcrumbs')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.breadcrumbs.label'))
                        ->default(config('filament-white-label.defaults.breadcrumbs', true))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.breadcrumbs.helper_text')),
                ]),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.dimensions'))->schema([
                    Select::make('metadata.container_width')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.content_width.label'))
                        ->default(null)
                        ->options(fn () => [
                            null => __('filament-white-label::filament-white-label.resource.options.default'),
                            '1024px' => __('filament-white-label::filament-white-label.resource.options.content_width.1024'),
                            '1280px' => __('filament-white-label::filament-white-label.resource.options.content_width.1280'),
                            'full' => __('filament-white-label::filament-white-label.resource.options.content_width.full'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.content_width.helper_text')),

                    Select::make('metadata.sidebar_width')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.sidebar_width.label'))
                        ->default(null)
                        ->options(fn () => [
                            null => __('filament-white-label::filament-white-label.resource.options.sidebar_width.320'),
                            '260px' => __('filament-white-label::filament-white-label.resource.options.sidebar_width.260'),
                            '300px' => __('filament-white-label::filament-white-label.resource.options.sidebar_width.300'),
                            '340px' => __('filament-white-label::filament-white-label.resource.options.sidebar_width.340'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.sidebar_width.helper_text')),

                    Select::make('metadata.heading_size')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.page_heading_size.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'small' => __('filament-white-label::filament-white-label.resource.options.page_heading_size.small'),
                            'large' => __('filament-white-label::filament-white-label.resource.options.page_heading_size.large'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.page_heading_size.helper_text')),

                    Select::make('metadata.nav_item_spacing')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.nav_item_spacing.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.nav_item_spacing.default'),
                            'compact' => __('filament-white-label::filament-white-label.resource.options.nav_item_spacing.compact'),
                            'spacious' => __('filament-white-label::filament-white-label.resource.options.nav_item_spacing.spacious'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.nav_item_spacing.helper_text')),
                ])->columns(2),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.footer'))->schema([
                    TextInput::make('metadata.footer_text')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.footer_text.label'))
                        ->maxLength(255)
                        ->placeholder(__('filament-white-label::filament-white-label.resource.fields.footer_text.placeholder'))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.footer_text.helper_text')),

                    Repeater::make('metadata.footer_links')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.footer_links.label'))
                        ->schema([
                            TextInput::make('label')
                                ->label(__('filament-white-label::filament-white-label.resource.fields.footer_links.link_label.label'))
                                ->required()
                                ->maxLength(100),
                            TextInput::make('url')
                                ->label(__('filament-white-label::filament-white-label.resource.fields.footer_links.link_url.label'))
                                ->required()
                                ->url()
                                ->maxLength(500),
                        ])
                        ->columns(2)
                        ->defaultItems(0)
                        ->addActionLabel(__('filament-white-label::filament-white-label.resource.fields.footer_links.add_link'))
                        ->collapsible()
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.footer_links.helper_text')),
                ]),
            ]);
    }
}
