<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QualificationsSeeder extends Seeder
{
    /**
     * Запуск сидера.
     */
    public function run(): void
    {
        $qualifications = [
            [
                'name' => 'Стажёр | Ученик',
                'min_seniority' => 0,
                'code' => 'TRN',
                'description' => 'Начинающий специалист, проходит обучение, работает под руководством опытных мастеров.'
            ],
            [
                'name' => 'Начальный уровень',
                'min_seniority' => 1,
                'code' => 'JUN',
                'description' => 'Младший специалист, способен выполнять простые задачи самостоятельно.'
            ],
            [
                'name' => 'Средний уровень',
                'min_seniority' => 3,
                'code' => 'MID',
                'description' => 'Опытный специалист, уверенно выполняет основные работы, может обучать новичков.'
            ],
            [
                'name' => 'Опытный специалист',
                'min_seniority' => 5,
                'code' => 'SEN',
                'description' => 'Высококвалифицированный работник, способен решать сложные задачи и работать без контроля.'
            ],
            [
                'name' => 'Эксперт | Высший уровень',
                'min_seniority' => 8,
                'code' => 'EXP',
                'description' => 'Мастер высшего уровня, принимает нестандартные решения, обучает персонал, разрабатывает новые методы.'
            ],
        ];

        foreach ($qualifications as $qualification) {
            DB::table('qualifications')->insertOrIgnore([
                'name' => $qualification['name'],
                'min_seniority' => $qualification['min_seniority'],
                'code' => $qualification['code'],
                'description' => $qualification['description'],
            ]);
        }
    }
}
