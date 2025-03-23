<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $originalBrands = [
            ['name' => 'Toyota', 'icon' => 'hf-toyota', 'description' => 'Японский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'Hyundai', 'icon' => 'hf-hyundai', 'description' => 'Южнокорейский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'KR', 'production_country_code' => 'KR'],
            ['name' => 'Ford', 'icon' => 'hf-ford', 'description' => 'Американский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'US', 'production_country_code' => 'US'],
            ['name' => 'Volkswagen', 'icon' => 'hf-volkswagen', 'description' => 'Немецкий производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Honda', 'icon' => 'hf-honda', 'description' => 'Японский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'BMW', 'icon' => 'hf-bmw', 'description' => 'Немецкий производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Mercedes-Benz', 'icon' => 'hf-mercedes-benz', 'description' => 'Немецкий производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Nissan', 'icon' => 'hf-nissan', 'description' => 'Японский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'Chevrolet', 'icon' => 'hf-chevrolet', 'description' => 'Американский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'US', 'production_country_code' => 'US'],
            ['name' => 'Audi', 'icon' => 'hf-audi', 'description' => 'Немецкий производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'KIA', 'icon' => 'hf-kia', 'description' => 'Южнокорейский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'KR', 'production_country_code' => 'KR'],
            ['name' => 'Mazda', 'icon' => 'hf-mazda', 'description' => 'Японский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'Subaru', 'icon' => 'hf-subaru', 'description' => 'Японский производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'Porsche', 'icon' => 'hf-porsche', 'description' => 'Немецкий производитель автомобилей.', 'is_original' => true, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Lexus', 'icon' => 'hf-lexus', 'description' => 'Премиальный бренд Toyota.', 'is_original' => true, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
        ];

        $duplicateBrands = [
            ['name' => 'Lynxauto', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'JP', 'production_country_code' => 'TW'],
            ['name' => 'CTR', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'KR', 'production_country_code' => 'KR'],
            ['name' => 'Zekkert', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'CN'],
            ['name' => 'Febi Bilstein', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Meyle', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Bosch', 'icon' => '', 'description' => 'Мировой лидер в производстве автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Denso', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'NGK', 'icon' => '', 'description' => 'Производитель свечей зажигания.', 'is_original' => false, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'KYB', 'icon' => '', 'description' => 'Производитель амортизаторов.', 'is_original' => false, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'Sachs', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'TRW', 'icon' => '', 'description' => 'Производитель тормозных систем.', 'is_original' => false, 'registration_country_code' => 'US', 'production_country_code' => 'US'],
            ['name' => 'Delphi', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'US', 'production_country_code' => 'US'],
            ['name' => 'Mahle', 'icon' => '', 'description' => 'Производитель фильтров и компонентов.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Mann-Filter', 'icon' => '', 'description' => 'Производитель фильтров.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Hella', 'icon' => '', 'description' => 'Производитель автоламп и электроники.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Brembo', 'icon' => '', 'description' => 'Производитель тормозных систем.', 'is_original' => false, 'registration_country_code' => 'IT', 'production_country_code' => 'IT'],
            ['name' => 'Continental', 'icon' => '', 'description' => 'Производитель шин и автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Goodyear', 'icon' => '', 'description' => 'Производитель шин.', 'is_original' => false, 'registration_country_code' => 'US', 'production_country_code' => 'US'],
            ['name' => 'Michelin', 'icon' => '', 'description' => 'Производитель шин.', 'is_original' => false, 'registration_country_code' => 'FR', 'production_country_code' => 'FR'],
            ['name' => 'Bridgestone', 'icon' => '', 'description' => 'Производитель шин.', 'is_original' => false, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'Yokohama', 'icon' => '', 'description' => 'Производитель шин.', 'is_original' => false, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'Aisin', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'JP', 'production_country_code' => 'JP'],
            ['name' => 'SKF', 'icon' => '', 'description' => 'Производитель подшипников.', 'is_original' => false, 'registration_country_code' => 'SE', 'production_country_code' => 'SE'],
            ['name' => 'Monroe', 'icon' => '', 'description' => 'Производитель амортизаторов.', 'is_original' => false, 'registration_country_code' => 'US', 'production_country_code' => 'US'],
            ['name' => 'Blue Print', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'GB', 'production_country_code' => 'GB'],
            ['name' => 'Optimal', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Ruville', 'icon' => '', 'description' => 'Производитель автозапчастей.', 'is_original' => false, 'registration_country_code' => 'DE', 'production_country_code' => 'DE'],
            ['name' => 'Lesjofors', 'icon' => '', 'description' => 'Производитель пружин.', 'is_original' => false, 'registration_country_code' => 'SE', 'production_country_code' => 'SE'],
        ];

        foreach ($originalBrands as $brandData) {
            Brand::create($brandData);
        }

        foreach ($duplicateBrands as $brandData) {
            Brand::create($brandData);
        }
    }
}
