<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'specialist_id',
        'work_date',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_day_off',
    ];

    //  преобразование типов
    protected $casts = [
        'work_date' => 'date',
        'is_day_off' => 'boolean',
    ];


    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }
}
