<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    public function specialists() {
        return $this->belongsToMany(Specialist::class, 'service_specialist');
    }

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'active',
    ];

    protected function casts(): array{
        return [
            'base_price' => 'decimal:2',
        ];
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }



    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'level_service')->withPivot('price', 'duration');
    }

}
