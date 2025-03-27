<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class Breadcrumb extends Component
{
    /**
     * Массив элементов хлебных крошек
     *
     * @var array
     */
    public $items = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->generateBreadcrumbs();
    }

    /**
     * Генерирует элементы хлебных крошек на основе текущего маршрута
     */
    protected function generateBreadcrumbs()
    {
        // Добавляем главную страницу
        $this->items[] = [
            'name' => 'Главная',
            'url' => route('dashboard.home'),
            'active' => false
        ];

        // Получаем текущий маршрут
        $routeName = Route::currentRouteName();
        
        if (!$routeName) {
            return;
        }

        // Разбиваем имя маршрута
        $segments = explode('.', $routeName);
        
        // Если меньше двух сегментов (dashboard.что-то) - выходим
        if (count($segments) < 2) {
            return;
        }

        // Проверяем, что первый сегмент - dashboard
        if ($segments[0] !== 'dashboard') {
            return;
        }

        // Специальная обработка для маршрутов статистики (dashboard.statistics.x)
        if (count($segments) > 2 && $segments[1] === 'statistics') {
            // Добавляем ссылку на раздел статистики
            $statisticsIndexRoute = 'dashboard.statistics.orders'; // Используем orders как дефолтный раздел
            $this->items[] = [
                'name' => 'Статистика',
                'url' => route($statisticsIndexRoute),
                'active' => false
            ];
            
            // Добавляем последний сегмент (вид статистики)
            $lastSegment = end($segments);
            $this->items[] = [
                'name' => $this->getActionName($lastSegment, 'statistics'),
                'url' => '#',
                'active' => true
            ];
            
            return;
        }

        // Добавляем промежуточные сегменты
        $currentSegments = [$segments[0]];
        
        // Пропускаем первый сегмент (dashboard) и последний (обрабатываем отдельно)
        for ($i = 1; $i < count($segments) - 1; $i++) {
            $currentSegments[] = $segments[$i];
            $currentRouteName = implode('.', $currentSegments) . '.index';
            
            // Если такой маршрут существует
            if (Route::has($currentRouteName)) {
                $this->items[] = [
                    'name' => $this->getSegmentName($segments[$i]),
                    'url' => route($currentRouteName),
                    'active' => false
                ];
            }
        }

        // Обрабатываем последний сегмент (action - index, create, show, edit, etc)
        $lastSegment = end($segments);
        
        // Определяем имя последнего сегмента и его активность
        $this->items[] = [
            'name' => $this->getActionName($lastSegment, $segments[count($segments) - 2]),
            'url' => '#',
            'active' => true
        ];
    }

    /**
     * Переводит сегмент маршрута в читаемое название
     */
    protected function getSegmentName($segment)
    {
        $names = [
            'roles' => 'Роли',
            'services' => 'Услуги',
            'service-types' => 'Типы услуг',
            'products' => 'Товары',
            'categories' => 'Категории',
            'brands' => 'Бренды',
            'models' => 'Модели',
            'orders' => 'Заказы',
            'statuses' => 'Статусы',
            'customers' => 'Клиенты',
            'vehicles' => 'Автомобили',
            'employees' => 'Сотрудники',
            'specializations' => 'Специальности',
            'qualifications' => 'Квалификации',
            'icons' => 'Иконки',
            'statistics' => 'Статистика',
            'loyalty-levels' => 'Бонусные программы',
        ];

        return $names[$segment] ?? ucfirst($segment);
    }

    /**
     * Переводит название действия в читаемое название
     */
    protected function getActionName($action, $resource)
    {
        // Если это индекс - возвращаем название ресурса
        if ($action === 'index') {
            return $this->getSegmentName($resource);
        }

        // Словарь действий для перевода
        $actionNames = [
            'create' => 'Создание',
            'show' => 'Просмотр',
            'edit' => 'Редактирование',
            'orders' => 'Заказы',
            'vehicles' => 'Автомобили',
            'customers' => 'Клиенты',
            'employees' => 'Сотрудники',
            'products' => 'Товары',
            'finance' => 'Финансы',
        ];

        // Специальная обработка для разделов статистики
        if ($resource === 'statistics') {
            $statisticsNames = [
                'orders' => 'Статистика заказов',
                'vehicles' => 'Статистика автомобилей',
                'customers' => 'Статистика клиентов',
                'employees' => 'Статистика сотрудников',
                'products' => 'Статистика товаров',
                'finance' => 'Финансовая статистика',
            ];
            
            return $statisticsNames[$action] ?? ucfirst($action);
        }

        // Если у нас есть прямой перевод действия
        if (isset($actionNames[$action])) {            
            // Для обычных CRUD действий добавляем название ресурса в ед. числе
            $resourceName = $this->getResourceSingularName($resource);
            
            if (in_array($action, ['create', 'show', 'edit'])) {
                return $actionNames[$action] . ' ' . $resourceName;
            }
            
            return $actionNames[$action];
        }
        
        // По умолчанию просто переводим первую букву в верхний регистр
        return ucfirst($action);
    }
    
    /**
     * Получает название ресурса в единственном числе
     */
    protected function getResourceSingularName($resource)
    {
        $singularNames = [
            'roles' => 'роли',
            'services' => 'услуги',
            'service-types' => 'типа услуги',
            'products' => 'товара',
            'categories' => 'категории',
            'brands' => 'бренда',
            'models' => 'модели',
            'orders' => 'заказа',
            'statuses' => 'статуса',
            'customers' => 'клиента',
            'vehicles' => 'автомобиля',
            'employees' => 'сотрудника',
            'specializations' => 'специализации',
            'qualifications' => 'квалификации',
            'icons' => 'иконки',
            'loyalty-levels' => 'уровня лояльности',
        ];
        
        return $singularNames[$resource] ?? rtrim($resource, 's');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.breadcrumb');
    }
} 