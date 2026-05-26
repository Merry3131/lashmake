<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'level_service');
    }

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match ($this->name) {
                    'master' => 'Мастер',
                    'top'    => 'Топ-мастер',
                    'lead'   => 'Ведущий специалист',
                    default  => $this->name,
                };
            }
        );
    }
}
