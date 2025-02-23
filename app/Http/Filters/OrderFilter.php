<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const STATUS = 'status';
    const DATES = 'dates';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::STATUS => [$this, 'status'],
            self::DATES => [$this, 'dates'],
            self::SORT => [$this, 'sort'],
        ];
    }

    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    $query->whereHas('customer', function ($query) use ($word) {
                        $query->where('name', 'like', '%' . $word . '%');
                    })
                        ->orWhereHas('purchases', function ($query) use ($word) {
                            $query->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhere('info', 'like', '%' . $word . '%');
                });
            }
        });
    }

    protected function status(Builder $builder, $value)
    {
        if (!is_null($value)) {
            $builder->where('status_id', $value);
        }
    }

    protected function dates(Builder $builder, $value)
    {
        $dates = explode('_', $value);

        if (count($dates) === 2) {
            $startDate = date('Y-m-d 00:00:00', strtotime($dates[0]));
            $endDate = date('Y-m-d 23:59:59', strtotime($dates[1]));

            $builder->whereBetween('date', [$startDate, $endDate]);
        }
    }

    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'date_asc':
                $builder->orderBy('created_at');
                break;
            case 'date_desc':
                $builder->orderBy('created_at', 'desc');
                break;
            default:
                $builder->orderBy('id', 'desc');
                break;
        }
    }
}
