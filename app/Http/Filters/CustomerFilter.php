<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class CustomerFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const LOYALTY_LEVEL_ID = 'loyalty_level_id';
    const LOYALTY_POINTS_MIN = 'loyalty_points_min';
    const LOYALTY_POINTS_MAX = 'loyalty_points_max';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::LOYALTY_LEVEL_ID => [$this, 'loyaltyLevelId'],
            self::LOYALTY_POINTS_MIN => [$this, 'loyaltyPointsMin'],
            self::LOYALTY_POINTS_MAX => [$this, 'loyaltyPointsMax'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (через связанного пользователя и info)
     */
    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    $query->where('info', 'like', '%' . $word . '%')
                        ->orWhereHas('user', function ($userQuery) use ($word) {
                            $userQuery->where('name', 'like', '%' . $word . '%')
                                ->orWhere('email', 'like', '%' . $word . '%')
                                ->orWhere('phone', 'like', '%' . $word . '%');
                        });
                });
            }
        });
    }

    /**
     * Фильтр по уровню лояльности
     */
    protected function loyaltyLevelId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('loyalty_level_id', $value);
        }
    }

    /**
     * Фильтр по минимальному количеству баллов лояльности
     */
    protected function loyaltyPointsMin(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('loyalty_points', '>=', $value);
        }
    }

    /**
     * Фильтр по максимальному количеству баллов лояльности
     */
    protected function loyaltyPointsMax(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('loyalty_points', '<=', $value);
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
            case 'name_asc': // По имени А-Я
                $builder->join('users', 'customers.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('customers.*');
                break;

            case 'name_desc': // По имени Я-А
                $builder->join('users', 'customers.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('customers.*');
                break;

            case 'loyalty_points_asc': // По баллам лояльности (от низких к высоким)
                $builder->orderBy('loyalty_points', 'asc');
                break;

            case 'loyalty_points_desc': // По баллам лояльности (от высоких к низким)
                $builder->orderBy('loyalty_points', 'desc');
                break;

            default: // По умолчанию: сортировка по ID в порядке убывания
                $builder->orderBy('id', 'desc');
                break;
        }
    }
}
