<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\WhiteLabelSettingsResource\Pages;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use FilamentWhiteLabel\Fonts\FontService;
use FilamentWhiteLabel\Resources\WhiteLabelSettingsResource;
use Illuminate\Database\Eloquent\Model;

class EditWhiteLabelSettings extends EditRecord
{
    protected static string $resource = WhiteLabelSettingsResource::class;

    public static function getTitle(): string
    {
        return __('filament-white-label::filament-white-label.resource.page.brand.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-white-label::filament-white-label.resource.page.brand.nav_label');
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
                Section::make(__('filament-white-label::filament-white-label.resource.sections.brand_identity'))->schema([
                    Grid::make(2)->schema([
                        TextInput::make('metadata.brand_name')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.brand_name.label'))
                            ->required()
                            ->maxLength(255)
                            ->default(config('app.name'))
                            ->placeholder(config('app.name')),

                        TextInput::make('metadata.brand_logo_height')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.logo_height.label'))
                            ->placeholder(__('filament-white-label::filament-white-label.resource.fields.logo_height.placeholder'))
                            ->helperText(__('filament-white-label::filament-white-label.resource.fields.logo_height.helper_text')),
                    ]),

                    Grid::make(2)->schema([
                        FileUpload::make('metadata.logo_path')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.logo_light.label'))
                            ->image()
                            ->imageResizeMode('contain')
                            ->directory(WhiteLabelSettingsResource::storageDirectory('logos'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp']),

                        FileUpload::make('metadata.dark_mode_logo_path')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.logo_dark.label'))
                            ->image()
                            ->imageResizeMode('contain')
                            ->directory(WhiteLabelSettingsResource::storageDirectory('logos'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp'])
                            ->helperText(__('filament-white-label::filament-white-label.resource.fields.logo_dark.helper_text')),
                    ]),

                    Grid::make(2)->schema([
                        FileUpload::make('metadata.favicon_path')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.favicon.label'))
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->directory(WhiteLabelSettingsResource::storageDirectory('favicons'))
                            ->disk(config('filament-white-label.disk', 'public'))
                            ->maxSize(512)
                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/svg+xml']),
                    ]),
                ])->columns(1),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.colors'))->schema([
                    Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.primary.label'))->schema([
                        Select::make('_ui_primary_palette')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.palette.label'))
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.primary');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_primary_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_primary_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.primary', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.primary')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.hex.label'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_primary_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.secondary.label'))->schema([
                        Select::make('_ui_secondary_palette')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.palette.label'))
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.secondary');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_secondary_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_secondary_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.secondary', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.secondary')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.hex.label'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_secondary_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.danger.label'))->schema([
                        Select::make('_ui_danger_palette')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.palette.label'))
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.danger');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_danger_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_danger_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.danger', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.danger')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.hex.label'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_danger_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.warning.label'))->schema([
                        Select::make('_ui_warning_palette')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.palette.label'))
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.warning');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_warning_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_warning_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.warning', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.warning')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.hex.label'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_warning_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.success.label'))->schema([
                        Select::make('_ui_success_palette')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.palette.label'))
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.success');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_success_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_success_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.success', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.success')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.hex.label'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_success_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                    Fieldset::make(__('filament-white-label::filament-white-label.resource.fields.colors.info.label'))->schema([
                        Select::make('_ui_info_palette')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.palette.label'))
                            ->options(fn () => static::paletteOptions())
                            ->native(true)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateHydrated(function ($state, $set, $get) {
                                $hex = $get('metadata.colors.info');
                                $palette = static::hexToPalette($hex);
                                if ($palette) {
                                    $set('_ui_info_palette', $palette);
                                } elseif ($hex) {
                                    $set('_ui_info_palette', 'custom');
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state && $state !== 'custom') {
                                    $hex = static::paletteHex($state);
                                    if ($hex) {
                                        $set('metadata.colors.info', $hex);
                                    }
                                }
                            }),
                        ColorPicker::make('metadata.colors.info')
                            ->label(__('filament-white-label::filament-white-label.resource.fields.colors.hex.label'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $palette = static::hexToPalette($state);
                                $set('_ui_info_palette', $palette ?? 'custom');
                            }),
                    ])->columns(2),
                ])->columns(2),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.typography'))->schema([
                    Select::make('metadata.font_family')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.font_family.label'))
                        ->options(fn () => FontService::fontOptions())
                        ->searchable()
                        ->default('Inter'),
                ]),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.styling'))->schema([
                    Select::make('metadata.border_radius')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.border_radius.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'none' => __('filament-white-label::filament-white-label.resource.options.none'),
                            'small' => __('filament-white-label::filament-white-label.resource.options.small'),
                            'medium' => __('filament-white-label::filament-white-label.resource.options.medium'),
                            'large' => __('filament-white-label::filament-white-label.resource.options.large'),
                            'pill' => __('filament-white-label::filament-white-label.resource.options.pill'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.border_radius.helper_text')),

                    Select::make('metadata.input_border_radius')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.input_border_radius.label'))
                        ->default(null)
                        ->options(fn () => [
                            null => __('filament-white-label::filament-white-label.resource.options.inherit'),
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'none' => __('filament-white-label::filament-white-label.resource.options.none'),
                            'small' => __('filament-white-label::filament-white-label.resource.options.small'),
                            'medium' => __('filament-white-label::filament-white-label.resource.options.medium'),
                            'large' => __('filament-white-label::filament-white-label.resource.options.large'),
                            'pill' => __('filament-white-label::filament-white-label.resource.options.pill'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.input_border_radius.helper_text')),

                    Select::make('metadata.shadow_intensity')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.shadow_intensity.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'none' => __('filament-white-label::filament-white-label.resource.options.none'),
                            'subtle' => __('filament-white-label::filament-white-label.resource.options.subtle'),
                            'pronounced' => __('filament-white-label::filament-white-label.resource.options.pronounced'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.shadow_intensity.helper_text')),

                    Select::make('metadata.badge_shape')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.badge_shape.label'))
                        ->default('default')
                        ->options(fn () => [
                            'default' => __('filament-white-label::filament-white-label.resource.options.default'),
                            'sharp' => __('filament-white-label::filament-white-label.resource.options.sharp'),
                            'rounded' => __('filament-white-label::filament-white-label.resource.options.rounded'),
                            'pill' => __('filament-white-label::filament-white-label.resource.options.pill'),
                        ])
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.badge_shape.helper_text')),
                ])->columns(2),

                Section::make(__('filament-white-label::filament-white-label.resource.sections.custom_css'))->schema([
                    Textarea::make('metadata.custom_css')
                        ->label(__('filament-white-label::filament-white-label.resource.fields.custom_css.label'))
                        ->rows(10)
                        ->maxLength(config('filament-white-label.security.max_css_length', 50000))
                        ->helperText(__('filament-white-label::filament-white-label.resource.fields.custom_css.helper_text'))
                        ->visible(fn () => ! config('filament-white-label.security.disable_custom_css', false)),
                ])->collapsed(),
            ]);
    }

    public static function paletteOptions(): array
    {
        $options = ['custom' => __('filament-white-label::filament-white-label.resource.fields.colors.custom_hex')];

        foreach (Color::all() as $name => $shades) {
            $hex = Color::convertToHex($shades[500]);
            $options[$name] = ucfirst($name).' · '.$hex;
        }

        return $options;
    }

    public static function colorHint(?string $hex): string
    {
        if (blank($hex)) {
            return '';
        }

        return sprintf(
            '<span style="display:inline-block;width:1rem;height:1rem;border-radius:50%%;background-color:%s;vertical-align:middle;border:1px solid rgba(0,0,0,.1)"></span>',
            e($hex),
        );
    }

    public static function paletteHex(?string $name): ?string
    {
        if (! $name) {
            return null;
        }

        $palettes = Color::all();

        if (isset($palettes[$name])) {
            return Color::convertToHex($palettes[$name][500]);
        }

        return null;
    }

    public static function hexToPalette(?string $hex): ?string
    {
        if (! $hex) {
            return null;
        }

        foreach (Color::all() as $name => $shades) {
            if (Color::convertToHex($shades[500]) === $hex) {
                return $name;
            }
        }

        return null;
    }

    public static function paletteData(): array
    {
        $data = [];

        foreach (Color::all() as $name => $shades) {
            $data[$name] = [
                'name' => ucfirst($name),
                'hex' => Color::convertToHex($shades[500]),
            ];
        }

        return $data;
    }
}
