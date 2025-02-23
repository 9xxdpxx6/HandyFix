<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;

class ServiceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceTypes = [
            [
                'name' => 'Диагностика',
                'icon' => 'hf-processor',
                'description' => 'Комплексная диагностика автомобиля.',
            ],
            [
                'name' => 'Ремонт двигателя',
                'icon' => 'hf-radiator',
                'description' => 'Ремонт и обслуживание двигателя.',
            ],
            [
                'name' => 'Кузовные работы',
                'icon' => 'hf-hammer',
                'description' => 'Ремонт кузова и покрасочные работы.',
            ],
            [
                'name' => 'Шиномонтаж',
                'icon' => 'hf-tire',
                'description' => 'Установка и замена шин.',
            ],
            [
                'name' => 'Электрика',
                'icon' => 'hf-lightning',
                'description' => 'Ремонт электрических систем автомобиля.',
            ],
            [
                'name' => 'Техническое обслуживание',
                'icon' => 'hf-oil-can-small',
                'description' => 'Регулярное техническое обслуживание.',
            ],
            [
                'name' => 'Замена масла',
                'icon' => 'hf-bottle',
                'description' => 'Профессиональная замена масла.',
            ],
            [
                'name' => 'Ремонт тормозной системы',
                'icon' => 'hf-brakes',
                'description' => 'Ремонт и замена тормозных колодок и дисков.',
            ],
        ];

        foreach ($serviceTypes as $serviceType) {
            ServiceType::insertOrIgnore($serviceType);
        }
    }
}
