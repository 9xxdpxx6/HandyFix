<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class EmployeeFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const QUALIFICATION_ID = 'qualification_id';
    const SPECIALIZATION_ID = 'specialization_id';
    const SENIORITY_MIN = 'seniority_min';
    const SENIORITY_MAX = 'seniority_max';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::QUALIFICATION_ID => [$this, 'qualificationId'],
            self::SPECIALIZATION_ID => [$this, 'specializationId'],
            self::SENIORITY_MIN => [$this, 'seniorityMin'],
            self::SENIORITY_MAX => [$this, 'seniorityMax'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по имени пользователя
     */
    protected function keyword(Builder $builder, $value)
    {
        $builder->whereHas('user', function ($q) use ($value) {
            $q->where('name', 'like', '%' . $value . '%');
        });
    }

    /**
     * Фильтр по квалификации
     */
    protected function qualificationId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('qualification_id', $value);
        }
    }

    /**
     * Фильтр по специализации
     */
    protected function specializationId(Builder $builder, $value)
    {
        if ($value) {
            $builder->where('specialization_id', $value);
        }
    }

    /**
     * Фильтр по минимальному стажу
     */
    protected function seniorityMin(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('seniority', '>=', $value);
        }
    }

    /**
     * Фильтр по максимальному стажу
     */
    protected function seniorityMax(Builder $builder, $value)
    {
        if ($value !== null) {
            $builder->where('seniority', '<=', $value);
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
                $builder->whereHas('user')
                    ->join('users', 'users.id', '=', 'employees.user_id')
                    ->orderBy('users.name', 'asc');
                break;

            case 'name_desc': // По имени Я-А
                $builder->whereHas('user')
                    ->join('users', 'users.id', '=', 'employees.user_id')
                    ->orderBy('users.name', 'desc');
                break;

            case 'seniority_asc': // По стажу (по возрастанию)
                $builder->orderBy('seniority', 'asc');
                break;

            case 'seniority_desc': // По стажу (по убыванию)
                $builder->orderBy('seniority', 'desc');
                break;

            case 'hire_date_asc': // По дате найма (старые)
                $builder->orderBy('hire_date', 'asc');
                break;

            case 'hire_date_desc': // По дате найма (новые)
                $builder->orderBy('hire_date', 'desc');
                break;

            default: // По умолчанию: сортировка по ID
                $builder->orderBy('id', 'desc');
                break;
        }
    }
} 