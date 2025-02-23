<?php

namespace Database\Seeders;

use App\Models\LoyaltyLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoyaltyLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loyaltyLevels = [
            [
                'name' => 'Bronze',
                'min_points' => 0,
                'discount' => 5.00,
                'priority' => 1,
                'description' => 'Начальный уровень лояльности с минимальной скидкой.',
            ],
            [
                'name' => 'Silver',
                'min_points' => 500,
                'discount' => 7.50,
                'priority' => 2,
                'description' => 'Средний уровень лояльности с умеренной скидкой.',
            ],
            [
                'name' => 'Gold',
                'min_points' => 1000,
                'discount' => 10.00,
                'priority' => 3,
                'description' => 'Привилегированный уровень лояльности с существенной скидкой.',
            ],
            [
                'name' => 'Platinum',
                'min_points' => 2000,
                'discount' => 15.00,
                'priority' => 4,
                'description' => 'Эксклюзивный уровень лояльности с максимальной скидкой.',
            ],
            [
                'name' => 'Diamond',
                'min_points' => 3000,
                'discount' => 20.00,
                'priority' => 5,
                'description' => 'Наивысший уровень лояльности с особыми привилегиями.',
            ],
        ];

        foreach ($loyaltyLevels as $level) {
            LoyaltyLevel::insertOrIgnore($level);
        }
    }
}
