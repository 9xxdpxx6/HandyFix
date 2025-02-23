<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Массив с категориями и подкатегориями
        $categories = [
            [
                'name' => 'Двигатель',
                'icon' => 'hf-engine',
                'description' => 'Компоненты двигателя автомобиля.',
                'parent_id' => null,
            ],
            [
                'name' => 'Топливная система',
                'icon' => 'hf-fuel-filter',
                'description' => 'Детали топливной системы.',
                'parent_id' => null,
            ],
            [
                'name' => 'Тормозная система',
                'icon' => 'hf-brakes',
                'description' => 'Компоненты тормозной системы.',
                'parent_id' => null,
            ],
            [
                'name' => 'Подвеска',
                'icon' => 'hf-suspension',
                'description' => 'Элементы подвески автомобиля.',
                'parent_id' => null,
            ],
            [
                'name' => 'Электрика',
                'icon' => 'hf-lightning',
                'description' => 'Электрические компоненты автомобиля.',
                'parent_id' => null,
            ],
            [
                'name' => 'Фильтры',
                'icon' => 'hf-oil-filter',
                'description' => 'Различные типы фильтров.',
                'parent_id' => null,
            ],
            [
                'name' => 'Масла и жидкости',
                'icon' => 'hf-bottle',
                'description' => 'Моторные масла, тормозные жидкости и другие.',
                'parent_id' => null,
            ],
            [
                'name' => 'Кузовные детали',
                'icon' => 'hf-hammer',
                'description' => 'Запчасти для кузова автомобиля.',
                'parent_id' => null,
            ],
            [
                'name' => 'Шины и диски',
                'icon' => 'hf-tire',
                'description' => 'Шины, диски и комплектующие.',
                'parent_id' => null,
            ],
            [
                'name' => 'Система охлаждения',
                'icon' => 'hf-radiator',
                'description' => 'Радиаторы, термостаты и другие компоненты.',
                'parent_id' => null,
            ],
            [
                'name' => 'Лампы',
                'icon' => 'hf-light-bulb',
                'description' => 'Автомобильные лампы и осветительные компоненты..',
                'parent_id' => 5, // Подкатегория "Электрика"
            ],
            [
                'name' => 'Амортизаторы',
                'icon' => 'hf-suspension',
                'description' => 'Амортизаторы и пружины.',
                'parent_id' => 4, // Подкатегория "Подвеска"
            ],
            [
                'name' => 'Тормозные колодки',
                'icon' => 'hf-brakes',
                'description' => 'Тормозные колодки и диски.',
                'parent_id' => 3, // Подкатегория "Тормозная система"
            ],
            [
                'name' => 'Воздушный фильтр',
                'icon' => 'hf-air-filter',
                'description' => 'Фильтры для очистки воздуха.',
                'parent_id' => 6, // Подкатегория "Фильтры"
            ],
            [
                'name' => 'Моторное масло',
                'icon' => 'hf-bottle',
                'description' => 'Масла для двигателей.',
                'parent_id' => 7, // Подкатегория "Масла и жидкости"
            ],
        ];

        // Добавляем категории в базу данных
        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
