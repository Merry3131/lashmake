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
        'discount_percent',
        'start_date',
        'end_date',
    ];

//    protected static array $typeLabels = [
//        'discount' => 'Скидка',
//        'model' => 'Требуется модель'
//    ];
//
//    //для образения к акксессору
//    protected function typeLabel(): Attribute{
//        return Attribute::make(
//            get: fn () => self::$typeLabels[$this->type] ?? $this->type,
//        );
//    }



    public function specialist()
    {
        return $this->belongsTo(Specialist::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getDiscountedPrices()
    {
        // Если акция привязана к конкретному мастеру
        if ($this->specialist_id) {
            $specialist = $this->specialist; // предполагаем, что Specialist имеет level_id
            if (!$specialist || !$specialist->level_id) {
                return null;
            }

            $levelService = LevelService::where('service_id', $this->service_id)
                ->where('level_id', $specialist->level_id)
                ->first();

            if (!$levelService) {
                return null;
            }

            $originalPrice = $levelService->price;
            $discounted = round($originalPrice * (1 - $this->discount_percent / 100), 2);

            return [
                'level_name'       => $levelService->level->display_name ?? $levelService->level->name,
                'original_price'   => $originalPrice,
                'discounted_price' => $discounted,
                'duration'         => $levelService->duration,
                'is_personal'      => true,
            ];
        }

        // Общая акция (на всех мастеров) – возвращаем цены для всех уровней услуги
        $levelServices = LevelService::where('service_id', $this->service_id)
            ->with('level')
            ->get();

        $result = [];
        foreach ($levelServices as $ls) {
            $originalPrice = $ls->price;
            $discounted = round($originalPrice * (1 - $this->discount_percent / 100), 2);
            $result[] = [
                'level_name'       => $ls->level->display_name ?? $ls->level->name,
                'original_price'   => $originalPrice,
                'discounted_price' => $discounted,
                'duration'         => $ls->duration,
            ];
        }
        return $result;
    }

    /**
     * Аксессор для отображения цены со скидкой в админ-таблице.
     */
    public function getPriceDisplayAttribute(): string
    {
        $prices = $this->getDiscountedPrices();

        if (!$prices) {
            return '—';
        }

        // Персональная акция – одна цена
        if (isset($prices['is_personal']) && $prices['is_personal'] === true) {
            return number_format($prices['discounted_price'], 0, '.', ' ') . ' ₽';
        }

        // Общая акция – диапазон цен
        $discountedValues = array_column($prices, 'discounted_price');
        if (empty($discountedValues)) {
            return '—';
        }
        $min = min($discountedValues);
        $max = max($discountedValues);

        if ($min == $max) {
            return number_format($min, 0, '.', ' ') . ' ₽';
        }
        return number_format($min, 0, '.', ' ') . ' – ' . number_format($max, 0, '.', ' ') . ' ₽';
    }
}
