<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Works extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
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
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

//    public function categories(){
//        return $this->belongsTo(Category::class, 'category_id');
//    }
}
