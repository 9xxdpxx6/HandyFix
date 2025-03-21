<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (name и description)
     */
    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    // Поиск по имени и описанию категории
                    $query->where('name', 'like', '%' . $word . '%')
                        ->orWhere('description', 'like', '%' . $word . '%');
                });
            }
        });
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

            default: // По умолчанию: сортировка по ID в порядке убывания
                $builder->orderBy('id', 'desc');
                break;
        }
    }
}
