<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialist extends Model
{
    protected $fillable=[
        'user_id',
        'level_id',
        'experience',
        'bio'
    ];
    public function service_specialist() {
        return $this->belongsToMany(Service::class, 'service_specialist');
    }

    public function user()
    {
        // специалист принаджелит пользователю
        return $this->belongsTo(User::class, 'user_id');
    }
    

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

//    public function prices(){
//        return $this->hasMany(LevelService::class, 'level', 'level');
//    }

//    protected function levelName(): Attribute
//    {
//        return Attribute::make(
//            get: fn () => [
//                'master' => 'Мастер',
//                'top'    => 'Топ-мастер',
//                'lead'   => 'Ведущий специалист',
//            ][$this->level] ?? $this->level,
//        );
//    }
}
