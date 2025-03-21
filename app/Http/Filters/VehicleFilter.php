<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class VehicleFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const CUSTOMER_ID = 'customer_id';
    const MODEL_ID = 'model_id';
    const BRAND_ID = 'brand_id';
    const YEAR_MIN = 'year_min';
    const YEAR_MAX = 'year_max';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::CUSTOMER_ID => [$this, 'customerId'],
            self::MODEL_ID => [$this, 'modelId'],
            self::BRAND_ID => [$this, 'brandId'],
            self::YEAR_MIN => [$this, 'yearMin'],
            self::YEAR_MAX => [$this, 'yearMax'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (license_plate и vin)
     */
    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    $query->where('license_plate', 'like', '%' . $word . '%')
                        ->orWhere('vin', 'like', '%' . $word . '%');
                });
            }
        });
    }

    /**
     * Фильтр по клиенту
     */
    protected function customerId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('customer_id', $value);
        }
    }

    /**
     * Фильтр по модели автомобиля
     */
    protected function modelId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('model_id', $value);
        }
    }

    /**
     * Фильтр по бренду автомобиля (через связь с model)
     */
    protected function brandId(Builder $builder, $value)
    {
        if ($value) {
            $builder->whereHas('model', function ($query) use ($value) {
                $query->where('brand_id', $value);
            });
        }
    }

    /**
     * Фильтр по минимальному году выпуска
     */
    protected function yearMin(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('year', '>=', $value);
        }
    }

    /**
     * Фильтр по максимальному году выпуска
     */
    protected function yearMax(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('year', '<=', $value);
        }
    }

    /**
     * Ограничение количества элементов
     */
    protected function limit(Builder $builder, $value)
    {
        if ($value) {
            $builder->limit($value);
        }
    }

    /**
     * Сортировка
     */
    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'year_asc': // По году выпуска (от раннего к позднему)
                $builder->orderBy('year', 'asc');
                break;

            case 'year_desc': // По году выпуска (от позднего к раннему)
                $builder->orderBy('year', 'desc');
                break;

            case 'license_plate_asc': // По госномеру (А-Я)
                $builder->orderBy('license_plate', 'asc');
                break;

            case 'license_plate_desc': // По госномеру (Я-А)
                $builder->orderBy('license_plate', 'desc');
                break;

            default: // По умолчанию: сортировка по ID в порядке убывания
                $builder->orderBy('id', 'desc');
                break;
        }
    }
} 