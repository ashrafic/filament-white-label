<?php

declare(strict_types=1);

namespace FilamentWhiteLabel\Models;

use FilamentWhiteLabel\Events\WhiteLabelSettingsDeleted;
use FilamentWhiteLabel\Events\WhiteLabelSettingsSaved;
use FilamentWhiteLabel\Security\CssSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WhiteLabelSettings extends Model
{
    protected $table = 'white_label_settings';

    protected $fillable = [
        'tenant_type',
        'tenant_id',
        'panel_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $dispatchesEvents = [
        'saved' => WhiteLabelSettingsSaved::class,
        'deleted' => WhiteLabelSettingsDeleted::class,
    ];

    public function tenant(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            $metadata = $model->metadata ?? [];

            if (! empty($metadata['custom_css'])) {
                $metadata['custom_css'] = CssSanitizer::sanitize($metadata['custom_css']);
            }

            $model->metadata = $metadata;
        });
    }
}
