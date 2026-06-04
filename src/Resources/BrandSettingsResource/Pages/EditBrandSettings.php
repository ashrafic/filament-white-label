<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Resources\BrandSettingsResource\Pages;

use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use FilamentWhiteLabel\Models\BrandSettings;
use FilamentWhiteLabel\Resources\BrandSettingsResource;
use Illuminate\Database\Eloquent\Model;

class EditBrandSettings extends EditRecord
{
    protected static string $resource = BrandSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(int | string $record = null): void
    {
        $this->record = $this->resolveRecord();

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    protected function resolveRecord(mixed $key = null): Model
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            $settings = BrandSettings::query()
                ->where('tenant_type', $tenant->getMorphClass())
                ->where('tenant_id', $tenant->getKey())
                ->first();

            if (! $settings) {
                $settings = BrandSettings::create([
                    'tenant_type' => $tenant->getMorphClass(),
                    'tenant_id' => $tenant->getKey(),
                    'brand_name' => $tenant->name ?? config('app.name'),
                    'font_family' => config('filament-white-label.defaults.font_family', 'Inter'),
                    'colors' => config('filament-white-label.defaults.colors'),
                ]);
            }

            return $settings;
        }

        $settings = BrandSettings::query()
            ->whereNull('tenant_type')
            ->whereNull('tenant_id')
            ->first();

        if (! $settings) {
            $settings = BrandSettings::create([
                'brand_name' => config('filament-white-label.defaults.brand_name'),
                'font_family' => config('filament-white-label.defaults.font_family', 'Inter'),
                'colors' => config('filament-white-label.defaults.colors'),
            ]);
        }

        return $settings;
    }
}