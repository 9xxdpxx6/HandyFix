<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;

class SpecializationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Список специализаций
        $specializations = [
            ['name' => 'Кузовщик', 'code' => 'BODY'],
            ['name' => 'Моторист', 'code' => 'ENGN'],
            ['name' => 'Электрик', 'code' => 'ELEC'],
            ['name' => 'Автомеханик', 'code' => 'MECH'],
            ['name' => 'Диагност', 'code' => 'DIAG'],
            ['name' => 'Автомаляр', 'code' => 'PNTR'],
            ['name' => 'Шиномонтажник', 'code' => 'TIRE'],
            ['name' => 'Администратор', 'code' => 'ADMN'],
            ['name' => 'Мастер-приёмщик', 'code' => 'MNGR'],
            ['name' => 'Установщик доп. оборудования', 'code' => 'TNNG'],
        ];

        // Вставка данных в базу
        foreach ($specializations as $specialization) {
            Specialization::insertOrIgnore($specialization);
        }
    }
}
