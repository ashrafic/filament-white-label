<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Pages\Auth;

use Filament\Pages\Auth\Login;
use FilamentWhiteLabel\Services\BrandResolver;

class BrandedLogin extends Login
{
    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'brandName' => BrandResolver::brandName(),
            'brandLogo' => BrandResolver::logoUrl(),
            'brandColors' => BrandResolver::colors(),
        ]);
    }
}