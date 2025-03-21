<?php

namespace App\Http\Filters;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class IconFilter
{
    protected $queryParams = [];

    /**
     * IconFilter constructor.
     *
     * @param array $queryParams
     */
    public function __construct(array $queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * Применяет фильтрацию к массиву иконок и возвращает объект пагинации
     *
     * @param array $icons Массив иконок
     * @return LengthAwarePaginator Пагинированный и отфильтрованный массив иконок
     */
    public function apply(array $icons): LengthAwarePaginator
    {
        // Фильтрация по ключевому слову
        if (isset($this->queryParams['keyword']) && !empty($this->queryParams['keyword'])) {
            $keyword = strtolower($this->queryParams['keyword']);
            $icons = array_filter($icons, function($icon, $name) use ($keyword) {
                return str_contains(strtolower($name), $keyword) || 
                      str_contains(strtolower($icon['keywords']), $keyword);
            }, ARRAY_FILTER_USE_BOTH);
        }
        
        // Сортировка
        if (isset($this->queryParams['sort']) && !empty($this->queryParams['sort'])) {
            $sort = $this->queryParams['sort'];
            
            // Создаем копию для сортировки
            $sortedIcons = $icons;
            
            switch ($sort) {
                case 'name_asc':
                    ksort($sortedIcons);
                    break;
                case 'name_desc':
                    krsort($sortedIcons);
                    break;
                default:
                    // По умолчанию не сортируем
                    break;
            }
            
            $icons = $sortedIcons;
        }
        
        // Параметры для пагинации (показываем все на одной странице)
        $page = 1;
        $total = count($icons);
        $perPage = $total > 0 ? $total : 1; // Используем общее количество как размер страницы
        
        // Создаем объект пагинации
        $paginator = new LengthAwarePaginator(
            $icons,
            $total,
            $perPage,
            $page,
            [
                'path' => Request::url(),
                'query' => $this->queryParams,
            ]
        );
        
        return $paginator;
    }
} 