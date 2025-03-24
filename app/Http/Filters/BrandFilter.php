<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class BrandFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const IS_ORIGINAL = 'is_original';
    const REGISTRATION_COUNTRY_CODE = 'registration_country_code';
    const PRODUCTION_COUNTRY_CODE = 'production_country_code';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::IS_ORIGINAL => [$this, 'isOriginal'],
            self::REGISTRATION_COUNTRY_CODE => [$this, 'registrationCountryCode'],
            self::PRODUCTION_COUNTRY_CODE => [$this, 'productionCountryCode'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    protected function keyword(Builder $builder, $value)
    {
        $words = explode(' ', $value);

        $builder->where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->where(function ($query) use ($word) {
                    // Поиск по имени и описанию бренда
                    $query->where('name', 'like', '%' . $word . '%')
                        ->orWhere('description', 'like', '%' . $word . '%');
                });
            }
        });
    }

    protected function isOriginal(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('is_original', $value);
        }
    }

    protected function registrationCountryCode(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('registration_country_code', $value);
        }
    }

    protected function productionCountryCode(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('production_country_code', $value);
        }
    }

    protected function limit(Builder $builder, $value)
    {
        if ($value) {
            $builder->limit($value);
        }
    }

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
