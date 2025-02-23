<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $brands = Brand::all();

        $productsData = [
            'Двигатель' => [
                ['name' => 'Поршень двигателя', 'description' => 'Комплект поршней для двигателя.', 'price' => 5000.00],
                ['name' => 'Клапан впускной', 'description' => 'Впускной клапан для двигателя.', 'price' => 1200.00],
                ['name' => 'Прокладка ГБЦ', 'description' => 'Прокладка головки блока цилиндров.', 'price' => 800.00],
            ],
            'Топливная система' => [
                ['name' => 'Топливный насос', 'description' => 'Электрический топливный насос.', 'price' => 3500.00],
                ['name' => 'Форсунка топливная', 'description' => 'Топливная форсунка для инжектора.', 'price' => 2000.00],
                ['name' => 'Датчик уровня топлива', 'description' => 'Датчик уровня топлива в баке.', 'price' => 900.00],
            ],
            'Тормозная система' => [
                ['name' => 'Тормозные колодки', 'description' => 'Комплект тормозных колодок.', 'price' => 1500.00],
                ['name' => 'Тормозной диск', 'description' => 'Передний тормозной диск.', 'price' => 2500.00],
                ['name' => 'Тормозной шланг', 'description' => 'Гибкий тормозной шланг.', 'price' => 600.00],
            ],
            'Подвеска' => [
                ['name' => 'Амортизатор передний', 'description' => 'Передний амортизатор подвески.', 'price' => 4000.00],
                ['name' => 'Стойка стабилизатора', 'description' => 'Стойка стабилизатора поперечной устойчивости.', 'price' => 700.00],
                ['name' => 'Пружина подвески', 'description' => 'Пружина передней подвески.', 'price' => 1800.00],
            ],
            'Электрика' => [
                ['name' => 'Генератор', 'description' => 'Автомобильный генератор переменного тока.', 'price' => 6000.00],
                ['name' => 'Стартер', 'description' => 'Стартер для запуска двигателя.', 'price' => 4500.00],
                ['name' => 'Лампа галогенная', 'description' => 'Галогенная лампа H7.', 'price' => 300.00],
            ],
            'Фильтры' => [
                ['name' => 'Масляный фильтр', 'description' => 'Фильтр для моторного масла.', 'price' => 400.00],
                ['name' => 'Воздушный фильтр', 'description' => 'Фильтр для очистки воздуха.', 'price' => 500.00],
                ['name' => 'Топливный фильтр', 'description' => 'Фильтр для очистки топлива.', 'price' => 600.00],
            ],
            'Масла и жидкости' => [
                ['name' => 'Моторное масло 5W-40', 'description' => 'Синтетическое моторное масло.', 'price' => 1200.00],
                ['name' => 'Тормозная жидкость DOT4', 'description' => 'Жидкость для тормозной системы.', 'price' => 400.00],
                ['name' => 'Охлаждающая жидкость', 'description' => 'Антифриз для системы охлаждения.', 'price' => 800.00],
            ],
            'Кузовные детали' => [
                ['name' => 'Бампер передний', 'description' => 'Передний бампер для автомобиля.', 'price' => 7000.00],
                ['name' => 'Зеркало заднего вида', 'description' => 'Наружное зеркало заднего вида.', 'price' => 2000.00],
                ['name' => 'Дверь передняя', 'description' => 'Передняя дверь для автомобиля.', 'price' => 10000.00],
            ],
            'Шины и диски' => [
                ['name' => 'Шина летняя 205/55 R16', 'description' => 'Летняя шина размером 205/55 R16.', 'price' => 3500.00],
                ['name' => 'Диск литой R16', 'description' => 'Литой диск размером R16.', 'price' => 15000.00],
                ['name' => 'Шина зимняя 205/55 R16', 'description' => 'Зимняя шина размером 205/55 R16.', 'price' => 4000.00],
            ],
            'Система охлаждения' => [
                ['name' => 'Радиатор охлаждения', 'description' => 'Радиатор системы охлаждения.', 'price' => 5000.00],
                ['name' => 'Термостат', 'description' => 'Термостат для системы охлаждения.', 'price' => 1200.00],
                ['name' => 'Водяной насос', 'description' => 'Водяной насос (помпа).', 'price' => 3000.00],
            ],
        ];

        foreach ($categories as $category) {
            if (isset($productsData[$category->name])) {
                foreach ($productsData[$category->name] as $product) {
                    $brand = $brands->random();
                    Product::create([
                        'name' => $product['name'],
                        'description' => $product['description'],
                        'price' => $product['price'],
                        'quantity' => rand(0, 50),
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]);
                }
            }
        }
    }
}
