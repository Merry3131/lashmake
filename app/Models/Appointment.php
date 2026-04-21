<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'client_id',
        'specialist_id',
        'service_id',
        'appointment_at',
        'final_price',
        'status',
        'prepayment_deadline',
        'notes',
    ];

    public function service(){
        return $this->belongsTo(Service::class);
    }
}
