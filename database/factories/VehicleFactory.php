<?php

namespace Database\Factories;

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

        $brand = Brand::where('is_original', true)->inRandomOrder()->first();

        $modelsByBrand = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Land Cruiser', 'Prius'],
            'Hyundai' => ['Solaris', 'Elantra', 'Tucson', 'Santa Fe', 'Creta'],
            'Ford' => ['Focus', 'Fiesta', 'Explorer', 'Mustang', 'Escape'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo', 'Jetta'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'HR-V', 'Odyssey'],
            'BMW' => ['3 Series', '5 Series', 'X5', 'X3', '7 Series'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'GLC', 'GLE'],
            'Nissan' => ['Almera', 'Qashqai', 'X-Trail', 'Murano', 'Pathfinder'],
            'Chevrolet' => ['Cruze', 'Malibu', 'Equinox', 'Tahoe', 'Silverado'],
            'Audi' => ['A4', 'A6', 'Q5', 'Q7', 'TT'],
            'Kia' => ['Rio', 'Cerato', 'Sportage', 'Sorento', 'K5'],
            'Mazda' => ['3', '6', 'CX-5', 'CX-9', 'MX-5'],
            'Subaru' => ['Forester', 'Outback', 'Impreza', 'Legacy', 'WRX'],
            'Porsche' => ['911', 'Cayenne', 'Panamera', 'Macan', 'Taycan'],
            'Lexus' => ['ES', 'RX', 'NX', 'GX', 'LX'],
        ];

        $models = $modelsByBrand[$brand->name] ?? [];
        $model = $this->faker->randomElement($models);

        $year = $this->faker->numberBetween(1990, date('Y'));

        $licensePlate = $this->generateRussianLicensePlate();

        $vin = $this->faker->boolean(80)
            ? $this->faker->unique()->regexify('[A-HJ-NPR-Z0-9]{17}')
            : $this->faker->unique()->regexify('[A-Z0-9]{6,12}');

        $mileage = $this->faker->numberBetween(0, (date('Y') - $year) * 20000);

        return [
            'customer_id' => $customer->id,
            'brand_id' => $brand->id,
            'model' => $model,
            'year' => $year,
            'license_plate' => $licensePlate,
            'vin' => $vin,
            'mileage' => $mileage,
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
