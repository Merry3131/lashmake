<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function specialists() {
        return $this->belongsToMany(Specialist::class, 'service_specialist');
    }

    protected $fillable = [
        'name',
        'description',
        'category',
        'base_price',
        'duration',
    ];

    protected function casts(): array{
        return [
            'base_price' => 'decimal:2',
        ];
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}
