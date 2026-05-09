<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    protected $fillable=[
        'user_id',
        'level',
        'experience',
        'bio'
    ];
    public function services()
    {
        // связь многие ко многим
        return $this->belongsToMany(Service::class, 'service_specialist');
    }

    public function user()
    {
        // специалист принаджелит пользователю
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prices(){
        return $this->hasMany(ServicePrice::class, 'level', 'level');
    }

    protected function levelName(): Attribute
    {
        return Attribute::make(
            get: fn () => [
                'master' => 'Мастер',
                'top'    => 'Топ-мастер',
                'lead'   => 'Ведущий специалист',
            ][$this->level] ?? $this->level,
        );
    }
}
