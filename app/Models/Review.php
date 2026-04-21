<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable =[
        'appointment_id',
        'user_id',
        'specialist_id',
        'rating',
        'comment',
    ];
    //связь с таблицей Пользователи
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    //связь с таблицей Специалисты
    public function specialist(){
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }
    //связь с таблицей Записи
    public function appointment(){
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
