<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::firstOrCreate(
            [
                'name' => 'Согласование',
                'color' => '#9b53b5',
            ]
        );

        Status::firstOrCreate(
            [
                'name' => 'В работе',
                'color' => '#4397e0',
            ]
        );

        Status::firstOrCreate(
            [
                'name' => 'Выполнен',
                'color' => '#1c992c',
            ]
        );

        Status::firstOrCreate(
            [
                'name' => 'Отказ',
                'color' => '#ba0000',
            ]
        );
    }
}
