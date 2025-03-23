<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class ProductsSeeder extends Seeder
{
    /**
     * Генерация случайного SKU (артикула).
     *
     * @param int $length
     * @return string
     */
    private function generateSku(int $length = 8): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $sku = '';
        for ($i = 0; $i < $length; $i++) {
            $sku .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $sku;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $brands = Brand::all();

        // Группы брендов по типам
        $carManufacturers = $brands->where('is_original', true);
        $partsManufacturers = $brands->where('is_original', false);
        $tireManufacturers = $partsManufacturers->filter(function ($brand) {
            return in_array($brand->name, ['Goodyear', 'Michelin', 'Bridgestone', 'Yokohama', 'Continental']);
        });
        $brakeManufacturers = $partsManufacturers->filter(function ($brand) {
            return in_array($brand->name, ['Brembo', 'TRW', 'Bosch', 'Febi Bilstein', 'Sachs']);
        });
        $filterManufacturers = $partsManufacturers->filter(function ($brand) {
            return in_array($brand->name, ['Mann-Filter', 'Mahle', 'Bosch', 'Denso', 'Blue Print']);
        });
        $suspensionManufacturers = $partsManufacturers->filter(function ($brand) {
            return in_array($brand->name, ['KYB', 'Monroe', 'Sachs', 'Lesjofors', 'Febi Bilstein']);
        });
        $electricalManufacturers = $partsManufacturers->filter(function ($brand) {
            return in_array($brand->name, ['Bosch', 'Denso', 'Hella', 'NGK', 'Continental']);
        });
        $oilManufacturers = $partsManufacturers->filter(function ($brand) {
            return in_array($brand->name, ['Bosch', 'Mobil', 'Castrol', 'Shell', 'Liqui Moly']);
        });

        $productsData = [
            'Двигатель' => [
                ['name' => 'Поршень двигателя', 'description' => 'Комплект поршней для двигателя.', 'price' => 5000.00, 'brands' => $partsManufacturers->whereIn('name', ['Mahle', 'Bosch', 'Aisin', 'Febi Bilstein', 'Optimal'])],
                ['name' => 'Клапан впускной', 'description' => 'Впускной клапан для двигателя.', 'price' => 1200.00, 'brands' => $partsManufacturers->whereIn('name', ['Mahle', 'Febi Bilstein', 'Optimal', 'Ruville', 'TRW'])],
                ['name' => 'Прокладка ГБЦ', 'description' => 'Прокладка головки блока цилиндров.', 'price' => 800.00, 'brands' => $partsManufacturers->whereIn('name', ['Mahle', 'Febi Bilstein', 'Optimal', 'Victor Reinz', 'Elring'])],
                ['name' => 'Коленчатый вал', 'description' => 'Коленчатый вал двигателя.', 'price' => 15000.00, 'brands' => $partsManufacturers->whereIn('name', ['Mahle', 'Febi Bilstein', 'Aisin', 'Optimal', 'Ruville'])],
                ['name' => 'Распредвал', 'description' => 'Распределительный вал двигателя.', 'price' => 9000.00, 'brands' => $partsManufacturers->whereIn('name', ['Febi Bilstein', 'Optimal', 'Ruville', 'Mahle', 'INA'])],
                ['name' => 'Ремень ГРМ', 'description' => 'Ремень газораспределительного механизма.', 'price' => 2200.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Continental', 'Febi Bilstein', 'Gates', 'Dayco'])],
                ['name' => 'Натяжитель ремня', 'description' => 'Натяжитель ремня ГРМ.', 'price' => 1800.00, 'brands' => $partsManufacturers->whereIn('name', ['INA', 'SKF', 'Febi Bilstein', 'Optimal', 'Ruville'])],
            ],
            'Топливная система' => [
                ['name' => 'Топливный насос', 'description' => 'Электрический топливный насос.', 'price' => 3500.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'Continental', 'Delphi', 'Febi Bilstein'])],
                ['name' => 'Форсунка топливная', 'description' => 'Топливная форсунка для инжектора.', 'price' => 2000.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'Continental', 'Delphi', 'Pierburg'])],
                ['name' => 'Датчик уровня топлива', 'description' => 'Датчик уровня топлива в баке.', 'price' => 900.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'Continental', 'Delphi', 'Hella'])],
                ['name' => 'Регулятор давления топлива', 'description' => 'Регулятор давления в топливной рампе.', 'price' => 1700.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'Continental', 'Delphi', 'Pierburg'])],
                ['name' => 'Топливная рампа', 'description' => 'Распределительная топливная рампа.', 'price' => 3200.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'Continental', 'Delphi', 'Pierburg'])],
                ['name' => 'Клапан EGR', 'description' => 'Клапан рециркуляции выхлопных газов.', 'price' => 4000.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'Continental', 'Delphi', 'Pierburg'])],
                ['name' => 'Датчик кислорода', 'description' => 'Лямбда-зонд для контроля смеси.', 'price' => 2900.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'NGK', 'Delphi', 'Hella'])],
            ],
            'Тормозная система' => [
                ['name' => 'Тормозные колодки', 'description' => 'Комплект тормозных колодок.', 'price' => 1500.00, 'brands' => $brakeManufacturers],
                ['name' => 'Тормозной диск', 'description' => 'Передний тормозной диск.', 'price' => 2500.00, 'brands' => $brakeManufacturers],
                ['name' => 'Тормозной шланг', 'description' => 'Гибкий тормозной шланг.', 'price' => 600.00, 'brands' => $brakeManufacturers],
                ['name' => 'Суппорт тормозной', 'description' => 'Тормозной суппорт в сборе.', 'price' => 4500.00, 'brands' => $brakeManufacturers],
                ['name' => 'Главный тормозной цилиндр', 'description' => 'Главный цилиндр тормозной системы.', 'price' => 3800.00, 'brands' => $brakeManufacturers],
                ['name' => 'Рабочий тормозной цилиндр', 'description' => 'Рабочий цилиндр барабанного тормоза.', 'price' => 1200.00, 'brands' => $brakeManufacturers],
                ['name' => 'Вакуумный усилитель тормозов', 'description' => 'Усилитель тормозной системы.', 'price' => 5000.00, 'brands' => $brakeManufacturers],
            ],
            'Подвеска' => [
                ['name' => 'Амортизатор передний', 'description' => 'Передний амортизатор подвески.', 'price' => 4000.00, 'brands' => $suspensionManufacturers],
                ['name' => 'Стойка стабилизатора', 'description' => 'Стойка стабилизатора поперечной устойчивости.', 'price' => 700.00, 'brands' => $suspensionManufacturers],
                ['name' => 'Пружина подвески', 'description' => 'Пружина передней подвески.', 'price' => 1800.00, 'brands' => $suspensionManufacturers],
                ['name' => 'Сайлентблок', 'description' => 'Сайлентблок рычага подвески.', 'price' => 500.00, 'brands' => $suspensionManufacturers],
                ['name' => 'Рычаг подвески', 'description' => 'Передний нижний рычаг подвески.', 'price' => 3500.00, 'brands' => $suspensionManufacturers],
                ['name' => 'Шаровая опора', 'description' => 'Шаровая опора рычага подвески.', 'price' => 900.00, 'brands' => $suspensionManufacturers],
                ['name' => 'Амортизатор задний', 'description' => 'Задний амортизатор подвески.', 'price' => 3600.00, 'brands' => $suspensionManufacturers],
            ],
            'Электрика' => [
                ['name' => 'Генератор', 'description' => 'Автомобильный генератор переменного тока.', 'price' => 6000.00, 'brands' => $electricalManufacturers],
                ['name' => 'Стартер', 'description' => 'Стартер для запуска двигателя.', 'price' => 4500.00, 'brands' => $electricalManufacturers],
                ['name' => 'Лампа галогенная', 'description' => 'Галогенная лампа H7.', 'price' => 300.00, 'brands' => $electricalManufacturers],
                ['name' => 'Аккумулятор', 'description' => 'Автомобильный аккумулятор 60Ah.', 'price' => 5000.00, 'brands' => $electricalManufacturers],
                ['name' => 'Катушка зажигания', 'description' => 'Катушка системы зажигания.', 'price' => 1800.00, 'brands' => $electricalManufacturers],
                ['name' => 'Свечи зажигания', 'description' => 'Комплект свечей зажигания.', 'price' => 1200.00, 'brands' => $electricalManufacturers],
                ['name' => 'Датчик положения коленвала', 'description' => 'Датчик положения коленчатого вала.', 'price' => 1500.00, 'brands' => $electricalManufacturers],
            ],
            'Фильтры' => [
                ['name' => 'Масляный фильтр', 'description' => 'Фильтр для моторного масла.', 'price' => 400.00, 'brands' => $filterManufacturers],
                ['name' => 'Воздушный фильтр', 'description' => 'Фильтр для очистки воздуха.', 'price' => 500.00, 'brands' => $filterManufacturers],
                ['name' => 'Топливный фильтр', 'description' => 'Фильтр для очистки топлива.', 'price' => 600.00, 'brands' => $filterManufacturers],
                ['name' => 'Салонный фильтр', 'description' => 'Фильтр очистки воздуха в салоне.', 'price' => 450.00, 'brands' => $filterManufacturers],
                ['name' => 'Фильтр АКПП', 'description' => 'Фильтр для автоматической коробки передач.', 'price' => 800.00, 'brands' => $filterManufacturers],
                ['name' => 'Сепаратор масляного фильтра', 'description' => 'Сепаратор масляного фильтра.', 'price' => 1200.00, 'brands' => $filterManufacturers],
                ['name' => 'Корпус масляного фильтра', 'description' => 'Корпус для масляного фильтра.', 'price' => 1500.00, 'brands' => $filterManufacturers],
            ],
            'Масла и жидкости' => [
                ['name' => 'Моторное масло 5W-40', 'description' => 'Синтетическое моторное масло 5W-40, 4л.', 'price' => 3200.00, 'brands' => $partsManufacturers->whereIn('name', ['Castrol', 'Shell', 'Mobil', 'Liqui Moly', 'Total'])],
                ['name' => 'Тормозная жидкость DOT4', 'description' => 'Жидкость для тормозной системы.', 'price' => 400.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'TRW', 'ATE', 'Febi Bilstein', 'Liqui Moly'])],
                ['name' => 'Охлаждающая жидкость', 'description' => 'Антифриз для системы охлаждения, 5л.', 'price' => 800.00, 'brands' => $partsManufacturers->whereIn('name', ['Febi Bilstein', 'Mobil', 'Shell', 'Liqui Moly', 'Total'])],
                ['name' => 'Масло для АКПП', 'description' => 'Трансмиссионное масло для АКПП, 1л.', 'price' => 950.00, 'brands' => $partsManufacturers->whereIn('name', ['ZF', 'Castrol', 'Mobil', 'Shell', 'Liqui Moly'])],
                ['name' => 'Масло для МКПП', 'description' => 'Трансмиссионное масло для МКПП, 1л.', 'price' => 850.00, 'brands' => $partsManufacturers->whereIn('name', ['ZF', 'Castrol', 'Mobil', 'Shell', 'Liqui Moly'])],
                ['name' => 'Жидкость для ГУР', 'description' => 'Гидравлическая жидкость для усилителя руля, 1л.', 'price' => 750.00, 'brands' => $partsManufacturers->whereIn('name', ['Febi Bilstein', 'Castrol', 'Mobil', 'Liqui Moly', 'Total'])],
                ['name' => 'Моторное масло 0W-20', 'description' => 'Синтетическое моторное масло 0W-20, 4л.', 'price' => 3500.00, 'brands' => $partsManufacturers->whereIn('name', ['Castrol', 'Shell', 'Mobil', 'Liqui Moly', 'Total'])],
            ],
            'Кузовные детали' => [
                ['name' => 'Бампер передний', 'description' => 'Передний бампер для автомобиля.', 'price' => 7000.00, 'brands' => $carManufacturers],
                ['name' => 'Зеркало заднего вида', 'description' => 'Наружное зеркало заднего вида.', 'price' => 2000.00, 'brands' => $carManufacturers],
                ['name' => 'Дверь передняя', 'description' => 'Передняя дверь для автомобиля.', 'price' => 10000.00, 'brands' => $carManufacturers],
                ['name' => 'Капот', 'description' => 'Капот автомобиля.', 'price' => 8500.00, 'brands' => $carManufacturers],
                ['name' => 'Крыло переднее', 'description' => 'Переднее крыло автомобиля.', 'price' => 4500.00, 'brands' => $carManufacturers],
                ['name' => 'Дверь задняя', 'description' => 'Задняя дверь для автомобиля.', 'price' => 9000.00, 'brands' => $carManufacturers],
                ['name' => 'Бампер задний', 'description' => 'Задний бампер для автомобиля.', 'price' => 6500.00, 'brands' => $carManufacturers],
            ],
            'Шины и диски' => [
                ['name' => 'Шина летняя 205/55 R16', 'description' => 'Летняя шина размером 205/55 R16.', 'price' => 3500.00, 'brands' => $tireManufacturers],
                ['name' => 'Диск литой R16', 'description' => 'Литой диск размером R16.', 'price' => 15000.00, 'brands' => $partsManufacturers->whereIn('name', ['OZ', 'BBS', 'ENKEI', 'AEZ', 'Kosei'])],
                ['name' => 'Шина зимняя 205/55 R16', 'description' => 'Зимняя шина размером 205/55 R16.', 'price' => 4000.00, 'brands' => $tireManufacturers],
                ['name' => 'Диск штампованный R16', 'description' => 'Штампованный диск размером R16.', 'price' => 2000.00, 'brands' => $partsManufacturers->whereIn('name', ['KFZ', 'ТЗСК', 'Magnetto', 'Trebl', 'Kronprinz'])],
                ['name' => 'Шина всесезонная 205/55 R16', 'description' => 'Всесезонная шина размером 205/55 R16.', 'price' => 3800.00, 'brands' => $tireManufacturers],
                ['name' => 'Диск кованый R16', 'description' => 'Кованый диск размером R16.', 'price' => 25000.00, 'brands' => $partsManufacturers->whereIn('name', ['OZ', 'BBS', 'RAYS', 'Work', 'Advan'])],
                ['name' => 'Комплект колпаков', 'description' => 'Комплект колпаков на колеса R16.', 'price' => 1200.00, 'brands' => $partsManufacturers->whereIn('name', ['SKF', 'Febi Bilstein', 'Meyle', 'Optimal', 'Ruville'])],
            ],
            'Система охлаждения' => [
                ['name' => 'Радиатор охлаждения', 'description' => 'Радиатор системы охлаждения.', 'price' => 5000.00, 'brands' => $partsManufacturers->whereIn('name', ['Denso', 'Valeo', 'NRF', 'Nissens', 'Hella'])],
                ['name' => 'Термостат', 'description' => 'Термостат для системы охлаждения.', 'price' => 1200.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Wahler', 'Vernet', 'Gates', 'Febi Bilstein'])],
                ['name' => 'Водяной насос', 'description' => 'Водяной насос (помпа).', 'price' => 3000.00, 'brands' => $partsManufacturers->whereIn('name', ['SKF', 'Aisin', 'Hepu', 'Graf', 'Febi Bilstein'])],
                ['name' => 'Вентилятор охлаждения', 'description' => 'Электровентилятор охлаждения радиатора.', 'price' => 2600.00, 'brands' => $partsManufacturers->whereIn('name', ['Bosch', 'Denso', 'Valeo', 'Hella', 'NRF'])],
                ['name' => 'Расширительный бачок', 'description' => 'Расширительный бачок охлаждающей жидкости.', 'price' => 700.00, 'brands' => $partsManufacturers->whereIn('name', ['Febi Bilstein', 'Meyle', 'Nissens', 'Valeo', 'Hella'])],
                ['name' => 'Патрубок радиатора', 'description' => 'Патрубок подачи охлаждающей жидкости.', 'price' => 500.00, 'brands' => $partsManufacturers->whereIn('name', ['Gates', 'Febi Bilstein', 'Dayco', 'Continental', 'Valeo'])],
                ['name' => 'Крышка радиатора', 'description' => 'Крышка радиатора с клапаном.', 'price' => 400.00, 'brands' => $partsManufacturers->whereIn('name', ['Gates', 'Febi Bilstein', 'Valeo', 'Mahle', 'Nissens'])],
            ],
        ];

        foreach ($categories as $category) {
            if (isset($productsData[$category->name])) {
                foreach ($productsData[$category->name] as $product) {
                    if (isset($product['brands']) && count($product['brands']) > 0) {
                        $brand = $product['brands']->random();
                    } else {
                        $brand = $brands->random();
                    }

                    Product::create([
                        'name' => $product['name'],
                        'sku' => $this->generateSku(rand(6, 13)),
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
