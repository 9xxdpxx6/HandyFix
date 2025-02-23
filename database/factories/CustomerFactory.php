<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use App\Models\LoyaltyLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $userIds = User::pluck('id')->toArray();

        $loyaltyLevelIds = LoyaltyLevel::pluck('id')->toArray();

        $userId = $this->getUniqueUserId($userIds);

        // Выбираем случайный уровень лояльности
        $loyaltyLevelId = $this->faker->randomElement($loyaltyLevelIds);

        return [
            'user_id' => $userId,
            'info' => $this->faker->realText,
            'loyalty_points' => $this->faker->numberBetween(0, 350000),
            'loyalty_level_id' => $loyaltyLevelId,
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
}
