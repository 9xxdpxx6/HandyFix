<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use App\Models\LoyaltyLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Распределяем даты создания с 10 апреля 2024 до текущего дня
        $createdAt = $this->faker->dateTimeBetween('2024-04-10', 'now');

        return [
            'user_id' => User::factory(),
            'info' => $this->faker->realText,
            'loyalty_points' => $this->faker->numberBetween(0, 350000),
            'loyalty_level_id' => LoyaltyLevel::factory(),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Получить уникальный user_id, который еще не используется в текущей фабрике.
     *
     * @param array $userIds
     * @return int|null
     */
    private function getUniqueUserId(array $userIds): ?int
    {
        static $usedUserIds = [];

        // Находим случайного пользователя, которого еще не использовали
        do {
            $userId = $this->faker->randomElement($userIds);
        } while (in_array($userId, $usedUserIds));

        // Добавляем его в список использованных
        $usedUserIds[] = $userId;

        return $userId;
    }
    
    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Customer $customer) {
            // Убедимся, что пользователь имеет роль клиента
            $user = $customer->user;
            if (!$user->hasRole('client')) {
                $clientRole = Role::where('name', 'client')->first();
                if ($clientRole) {
                    $user->assignRole($clientRole);
                }
            }
        });
    }
}
