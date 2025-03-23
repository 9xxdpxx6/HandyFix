<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ServiceFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const SERVICE_TYPE = 'service_type_id';
    const PRICE_MAX = 'price_max';
    const PRICE_MIN = 'price_min';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::SERVICE_TYPE => [$this, 'serviceType'],
            self::PRICE_MAX => [$this, 'priceMax'],
            self::PRICE_MIN => [$this, 'priceMin'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (name, description)
     */
    protected function keyword(Builder $builder, $value)
    {
        $builder->where(function ($query) use ($value) {
            $query->where('name', 'like', '%' . $value . '%')
                ->orWhere('description', 'like', '%' . $value . '%');
        });
    }

    /**
     * Фильтр по типу услуги
     */
    protected function serviceType(Builder $builder, $value)
    {
        $builder->where('service_type_id', $value);
    }

    /**
     * Фильтр по минимальной цене
     */
    protected function priceMin(Builder $builder, $value)
    {
        $builder->where('price', '>=', $value);
    }

    /**
     * Фильтр по максимальной цене
     */
    protected function priceMax(Builder $builder, $value)
    {
        $builder->where('price', '<=', $value);
    }

    /**
     * Сортировка
     */
    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'alphabet_asc': // По алфавиту А-Я
                $builder->orderBy('name', 'asc');
                break;

            case 'alphabet_desc': // По алфавиту Я-А
                $builder->orderBy('name', 'desc');
                break;

            case 'price_asc': // По цене (от низкой)
                $builder->orderBy('price', 'asc');
                break;

            case 'price_desc': // По цене (от высокой)
                $builder->orderBy('price', 'desc');
                break;

            default: // По умолчанию: ID по убыванию
                $builder->orderBy('id', 'desc');
                break;
        }
    }
} 