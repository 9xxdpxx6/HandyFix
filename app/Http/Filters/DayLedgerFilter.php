<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class DayLedgerFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const DATES = 'dates';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
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
                    $query->whereHas('materialEntries', function ($query) use ($word) {
                        $query->where('description', 'like', '%' . $word . '%');
                    })
                        ->orWhereHas('serviceEntries', function ($query) use ($word) {
                            $query->where('description', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('expenseEntries', function ($query) use ($word) {
                            $query->where('description', 'like', '%' . $word . '%');
                        });
                });
            }
        });
    }

    protected function dates(Builder $builder, $value)
    {
        $dates = explode('_', $value);

        if (count($dates) === 2) {
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));

            $builder->whereBetween('date', [$startDate, $endDate]);
        }
    }

    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'date_asc':
                $builder->orderBy('date', 'asc');
                break;
            case 'date_desc':
                $builder->orderBy('date', 'desc');
                break;
            default:
                $builder->orderBy('id', 'desc');
                break;
        }
    }
}
