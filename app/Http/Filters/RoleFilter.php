<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class RoleFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const PERMISSION = 'permission_id';
    const LIMIT = 'limit';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::PERMISSION => [$this, 'permission'],
            self::LIMIT => [$this, 'limit'],
            self::SORT => [$this, 'sort'],
        ];
    }

    /**
     * Поиск по ключевому слову (name)
     */
    protected function keyword(Builder $builder, $value)
    {
        $builder->where('name', 'like', '%' . $value . '%');
    }

    /**
     * Фильтр по разрешению
     */
    protected function permission(Builder $builder, $value)
    {
        if (!empty($value)) {
            // Если передан массив разрешений - используем логику "И"
            if (is_array($value)) {
                foreach ($value as $permissionId) {
                    $builder->whereHas('permissions', function ($query) use ($permissionId) {
                        $query->where('permissions.id', $permissionId);
                    });
                }
            } else {
                // Для обратной совместимости, если передано одно значение
                $builder->whereHas('permissions', function ($query) use ($value) {
                    $query->where('permissions.id', $value);
                });
            }
        }
    }

    /**
     * Ограничение количества элементов
     */
    protected function limit(Builder $builder, $value)
    {
        // Этот метод не требуется, так как limit будет применяться при пагинации
        // Но оставляем для согласованности с другими фильтрами
    }

    /**
     * Сортировка
     */
    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'name_asc': // По алфавиту А-Я
                $builder->orderBy('name', 'asc');
                break;

            case 'name_desc': // По алфавиту Я-А
                $builder->orderBy('name', 'desc');
                break;

            case 'id_asc': // По ID (по возрастанию)
                $builder->orderBy('id', 'asc');
                break;

            case 'id_desc': // По ID (по убыванию)
                $builder->orderBy('id', 'desc');
                break;

            default: // По умолчанию: ID по возрастанию
                $builder->orderBy('id', 'asc');
                break;
        }
    }
} 