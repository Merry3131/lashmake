<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Works extends Model
{
    protected $fillable = [
        'service_id',
        'specialist_id',
        'description',
    ];

    public function service(){
        return $this->belongsTo(Service::class, 'service_id');
    }
    public function specialist(){
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }

//    public function categories(){
//        return $this->belongsTo(Category::class, 'category_id');
//    }
}
