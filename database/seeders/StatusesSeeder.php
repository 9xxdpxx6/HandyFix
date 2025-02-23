<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Список статусов с HEX-цветами
        $statuses = [
            [
                'name' => 'Новый',
                'color' => '#4287f5', // Голубой
                'is_closing' => false,
            ],
            [
                'name' => 'В работе',
                'color' => '#de8a00', // Оранжевый
                'is_closing' => false,
            ],
            [
                'name' => 'Завершен',
                'color' => '#28a745', // Зеленый
                'is_closing' => true,
            ],
            [
                'name' => 'Отменен',
                'color' => '#dc3545', // Красный
                'is_closing' => false,
            ],
            [
                'name' => 'На проверке',
                'color' => '#c79f00', // Желтый
                'is_closing' => false,
            ],
            [
                'name' => 'Ожидает оплаты',
                'color' => '#6f42c1', // Фиолетовый
                'is_closing' => false,
            ],
        ];

        // Вставка данных в базу
        foreach ($statuses as $status) {
            Status::insertOrIgnore($status);
        }
    }
}
