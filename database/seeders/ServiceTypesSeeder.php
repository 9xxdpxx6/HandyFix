<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ServiceType::firstOrCreate(
            [
                'name' => 'Ремонт',
            ]
        );

        ServiceType::firstOrCreate(
            [
                'name' => 'Работа',
            ]
        );
    }
}
