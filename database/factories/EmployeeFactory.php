<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use App\Models\Qualification;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $qualificationIds = Qualification::pluck('id')->toArray();
        $specializationIds = Specialization::pluck('id')->toArray();

        // Распределяем даты создания по периоду 2023-2025
        $createdAt = $this->faker->dateTimeBetween('2024-01-01', '2025-12-31');
        
        // Дата приема на работу должна быть до или совпадать с датой создания записи
        $hireDate = $this->faker->dateTimeBetween('-10 years', $createdAt);

        return [
            'user_id' => $this->getUniqueUserId($userIds),
            'qualification_id' => $this->faker->randomElement($qualificationIds),
            'specialization_id' => $this->faker->randomElement($specializationIds),
            'fixed_salary' => $this->faker->randomFloat(2, 30000, 150000),
            'commission_rate' => $this->faker->randomFloat(2, 0, 10),
            'seniority' => $this->faker->numberBetween(0, 30),
            'hire_date' => $hireDate,
            'termination_date' => $this->faker->optional()->dateTimeBetween($createdAt, '+5 years'),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Создание сотрудников с учетом процентного распределения ролей.
     *
     * @param int $count
     * @return void
     */
    public function createWithRoles(int $count): void
    {
        // Процентное распределение ролей
        $roleDistribution = [
            'junior-mechanic' => 30,
            'mechanic' => 25,
            'senior-mechanic' => 10,
            'junior-manager' => 10,
            'manager' => 10,
            'senior-manager' => 5,
            'junior-accountant' => 5,
            'accountant' => 3,
            'senior-accountant' => 2,
            'moderator' => 1,
        ];

        // Расчет количества сотрудников для каждой роли
        $rolesCount = [];
        foreach ($roleDistribution as $role => $percentage) {
            $rolesCount[$role] = (int) round(($percentage / 100) * $count);
        }

        // Создание сотрудников для каждой роли
        foreach ($rolesCount as $role => $countForRole) {
            for ($i = 0; $i < $countForRole; $i++) {
                $employee = Employee::factory()->create();

                $user = $employee->user;
                if ($user) {
                    $user->assignRole($role);
                }
            }
        }
    }

    /**
     * Получить уникального пользователя из списка user_ids.
     *
     * @param array $userIds
     * @return int|null
     */
    private function getUniqueUserId(array $userIds): ?int
    {
        static $usedUserIds = [];

        if (empty($userIds)) {
            return null;
        }

        do {
            $userId = $this->faker->randomElement($userIds);
        } while (in_array($userId, $usedUserIds));

        $usedUserIds[] = $userId;

        return $userId;
    }
}
