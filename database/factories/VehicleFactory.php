<?php

namespace Database\Factories;

use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;
use App\Models\Customer;

class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::inRandomOrder()->first();

        $vehicleModel = VehicleModel::inRandomOrder()->first();

        $year = $this->faker->numberBetween(
            $vehicleModel->start_year ?? 1990,
            $vehicleModel->end_year ?? date('Y')
        );

        $licensePlate = $this->generateRussianLicensePlate();

        $vin = $this->faker->boolean(80)
            ? $this->faker->unique()->regexify('[A-HJ-NPR-Z0-9]{17}')
            : $this->faker->unique()->regexify('[A-Z0-9]{6,12}');

        $mileage = $this->faker->numberBetween(0, (date('Y') - $year) * 20000);
        
        // Распределяем даты создания по периоду 2023-2025
        // Дата создания должна быть не раньше даты создания клиента
        $customerCreatedAt = $customer->created_at;
        $endDate = new \DateTime();
        
        // Проверяем, что начальная дата не позже конечной
        $startDate = new \DateTime('2024-04-10');
        if ($customerCreatedAt instanceof \DateTime && $customerCreatedAt < $endDate) {
            if ($customerCreatedAt > $startDate) {
                $startDate = $customerCreatedAt;
            }
        }
        
        // Используем проверенные даты
        $createdAt = $this->faker->dateTimeBetween($startDate, $endDate);

        return [
            'customer_id' => $customer->id,
            'model_id' => $vehicleModel->id,
            'year' => $year,
            'license_plate' => $licensePlate,
            'vin' => $vin,
            'mileage' => $mileage,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Генерирует российский номерной знак в формате а123вс77 или а123вс177.
     *
     * @return string
     */
    private function generateRussianLicensePlate(): string
    {
        // Разрешенные кириллические буквы для номеров
        $letters = ['а', 'в', 'е', 'к', 'м', 'н', 'о', 'р', 'с', 'т', 'у', 'х'];

        // Генерируем случайную букву
        $firstLetter = $this->faker->randomElement($letters);
        $thirdLetter = $this->faker->randomElement($letters);
        $fourthLetter = $this->faker->randomElement($letters);

        // Генерируем цифры
        $numbers = $this->faker->numberBetween(100, 999);

        // Генерируем код региона
        $regionCode = $this->faker->numberBetween(1, 199);

        // Формат номера: А123ВС77 или А123ВС177
        $format = $this->faker->boolean(70)
            ? "{$firstLetter}{$numbers}{$thirdLetter}{$fourthLetter}" . sprintf('%02d', $regionCode)
            : "{$firstLetter}{$numbers}{$thirdLetter}{$fourthLetter}" . sprintf('%03d', $regionCode);

        // Гарантируем уникальность номера
        return $this->faker->unique()->lexify($format);
    }
}
