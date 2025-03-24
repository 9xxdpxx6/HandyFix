<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const CATEGORY_ID = 'category_id';
    const BRAND_ID = 'brand_id';
    const PRICE_MIN = 'price_min';
    const PRICE_MAX = 'price_max';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::CATEGORY_ID => [$this, 'categoryId'],
            self::BRAND_ID => [$this, 'brandId'],
            self::PRICE_MIN => [$this, 'priceMin'],
            self::PRICE_MAX => [$this, 'priceMax'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (name, sku и description)
     */
    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    $query->where('name', 'like', '%' . $word . '%')
                        ->orWhere('sku', 'like', '%' . $word . '%')
                        ->orWhere('description', 'like', '%' . $word . '%');
                });
            }
        });
    }

    /**
     * Фильтр по категории
     */
    protected function categoryId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('category_id', $value);
        }
    }

    /**
     * Фильтр по бренду
     */
    protected function brandId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('brand_id', $value);
        }
    }

    /**
     * Фильтр по минимальной цене
     */
    protected function priceMin(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('price', '>=', $value);
        }
    }

    /**
     * Фильтр по максимальной цене
     */
    protected function priceMax(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('price', '<=', $value);
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
            case 'alphabet_asc': // По алфавиту А-Я
                $builder->orderBy('name', 'asc');
                break;

            case 'alphabet_desc': // По алфавиту Я-А
                $builder->orderBy('name', 'desc');
                break;

            case 'price_asc': // По цене (от низкой к высокой)
                $builder->orderBy('price', 'asc');
                break;

            case 'price_desc': // По цене (от высокой к низкой)
                $builder->orderBy('price', 'desc');
                break;

            default: // По умолчанию: сортировка по ID в порядке убывания
                $builder->orderBy('id', 'desc');
                break;
        }
    }
} 