<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class VehicleModelFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const BRAND_ID = 'brand_id';
    const START_YEAR_MIN = 'start_year_min';
    const START_YEAR_MAX = 'start_year_max';
    const END_YEAR_MIN = 'end_year_min';
    const END_YEAR_MAX = 'end_year_max';
    const IS_FACELIFT = 'is_facelift';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::BRAND_ID => [$this, 'brandId'],
            self::START_YEAR_MIN => [$this, 'startYearMin'],
            self::START_YEAR_MAX => [$this, 'startYearMax'],
            self::END_YEAR_MIN => [$this, 'endYearMin'],
            self::END_YEAR_MAX => [$this, 'endYearMax'],
            self::IS_FACELIFT => [$this, 'isFacelift'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (name и generation)
     */
    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    $query->where('name', 'like', '%' . $word . '%')
                        ->orWhere('generation', 'like', '%' . $word . '%');
                });
            }
        });
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
     * Фильтр по минимальному году начала производства
     */
    protected function startYearMin(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('start_year', '>=', $value);
        }
    }

    /**
     * Фильтр по максимальному году начала производства
     */
    protected function startYearMax(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('start_year', '<=', $value);
        }
    }

    /**
     * Фильтр по минимальному году окончания производства
     */
    protected function endYearMin(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('end_year', '>=', $value);
        }
    }

    /**
     * Фильтр по максимальному году окончания производства
     */
    protected function endYearMax(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('end_year', '<=', $value);
        }
    }

    /**
     * Фильтр по наличию фейслифта
     */
    protected function isFacelift(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('is_facelift', $value);
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

            case 'year_asc': // По году начала производства (от раннего к позднему)
                $builder->orderBy('start_year', 'asc');
                break;

            case 'year_desc': // По году начала производства (от позднего к раннему)
                $builder->orderBy('start_year', 'desc');
                break;

            default: // По умолчанию: сортировка по ID в порядке убывания
                $builder->orderBy('id', 'desc');
                break;
        }
    }
} 