<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'service_id',
        'title',
        'specialist_id',
        'type',
        'discount_percent',
        'start_date',
        'end_date',
    ];

    protected static array $typeLabels = [
        'discount' => 'Скидка',
        'model' => 'Требуется модель'
    ];

    //для образения к акксессору
    protected function typeLabel(): Attribute{
        return Attribute::make(
            get: fn () => self::$typeLabels[$this->type] ?? $this->type,
        );
    }

    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
