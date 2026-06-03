<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BrandSettings extends Model
{
    protected $fillable = [
        'tenant_type',
        'tenant_id',
        'brand_name',
        'logo_path',
        'favicon_path',
        'font_family',
        'custom_css',
        'email_from_address',
        'email_from_name',
        'metadata',
    ];

    protected $casts = [
        'colors' => 'array',
        'metadata' => 'array',
    ];

    public function tenant(): MorphTo
    {
        return $this->morphTo();
    }
}