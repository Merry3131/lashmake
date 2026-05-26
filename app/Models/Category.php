<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = ['slug', 'display_name', 'description'];
    public function services(){
        return $this->hasMany(Service::class);
    }


}
