<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Specialist extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
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

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'specialist_id');
    }

    /**
     * Получить средний рейтинг специалиста (например: 4.8)
     */
    public function averageRating()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 5.0;
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
