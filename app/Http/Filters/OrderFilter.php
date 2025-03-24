<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const CUSTOMER_ID = 'customer_id';
    const MANAGER_ID = 'manager_id';
    const VEHICLE_ID = 'vehicle_id';
    const STATUS_ID = 'status_id';
    const TOTAL_MIN = 'total_min';
    const TOTAL_MAX = 'total_max';
    const DATE_FROM = 'date_from';
    const DATE_TO = 'date_to';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::CUSTOMER_ID => [$this, 'customerId'],
            self::MANAGER_ID => [$this, 'managerId'],
            self::VEHICLE_ID => [$this, 'vehicleId'],
            self::STATUS_ID => [$this, 'statusId'],
            self::TOTAL_MIN => [$this, 'totalMin'],
            self::TOTAL_MAX => [$this, 'totalMax'],
            self::DATE_FROM => [$this, 'dateFrom'],
            self::DATE_TO => [$this, 'dateTo'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (comment, note)
     */
    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    $query->where('comment', 'like', '%' . $word . '%')
                        ->orWhere('note', 'like', '%' . $word . '%');
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
     * Фильтр по менеджеру
     */
    protected function managerId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('manager_id', $value);
        }
    }

    /**
     * Фильтр по автомобилю
     */
    protected function vehicleId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('vehicle_id', $value);
        }
    }

    /**
     * Фильтр по статусу
     */
    protected function statusId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('status_id', $value);
        }
    }

    /**
     * Фильтр по минимальной сумме заказа
     */
    protected function totalMin(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('total', '>=', $value);
        }
    }

    /**
     * Фильтр по максимальной сумме заказа
     */
    protected function totalMax(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('total', '<=', $value);
        }
    }

    /**
     * Фильтр по дате начала периода
     */
    protected function dateFrom(Builder $builder, $value)
    {
        if ($value) {
            $builder->whereDate('created_at', '>=', $value);
        }
    }

    /**
     * Фильтр по дате окончания периода
     */
    protected function dateTo(Builder $builder, $value)
    {
        if ($value) {
            $builder->whereDate('created_at', '<=', $value);
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
            case 'date_asc': // По дате создания (от старых к новым)
                $builder->orderBy('created_at', 'asc');
                break;

            case 'date_desc': // По дате создания (от новых к старым)
                $builder->orderBy('created_at', 'desc');
                break;

            case 'total_asc': // По сумме заказа (от минимальной к максимальной)
                $builder->orderBy('total', 'asc');
                break;

            case 'total_desc': // По сумме заказа (от максимальной к минимальной)
                $builder->orderBy('total', 'desc');
                break;

            default: // По умолчанию: сортировка по ID в порядке убывания
                $builder->orderBy('id', 'desc');
                break;
        }
    }
}
