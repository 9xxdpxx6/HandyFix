<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\LoyaltyHistory;
use App\Models\Order;
use App\Models\ProductPrice;
use App\Models\Purchase;
use App\Models\ServiceEntry;
use App\Models\ServicePrice;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(LoyaltyLevelsSeeder::class);
        $this->call(SpecializationsSeeder::class);
        $this->call(QualificationsSeeder::class);
        $this->call(StatusesSeeder::class);
        $this->call(ServiceTypesSeeder::class);
        $this->call(ServicesSeeder::class);
        $this->call(BrandsSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(ProductsSeeder::class);

        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, 7450);
        $progressBar->start();

        // Создание данных с ProgressBar
        $this->seedWithProgress(User::class, 200, 'User', $progressBar);
        $this->seedWithProgress(Customer::class, 100, 'Customer', $progressBar);
        $this->seedEmployeesWithRoles(20, 'Employee', $progressBar);
        $this->seedWithProgress(Vehicle::class, 130, 'Vehicle', $progressBar);
        $this->seedWithProgress(Order::class, 1000, 'Order', $progressBar);
        $this->seedWithProgress(ServiceEntry::class, 2000, 'ServiceEntry', $progressBar);
        $this->seedWithProgress(LoyaltyHistory::class, 1000, 'LoyaltyHistory', $progressBar);
        $this->seedWithProgress(Purchase::class, 2000, 'Purchase', $progressBar);
        $this->seedWithProgress(ProductPrice::class, 1000, 'ProductPrice', $progressBar);
        $this->seedWithProgress(ServicePrice::class, 1000, 'ServicePrice', $progressBar);

        $progressBar->finish();
        $output->writeln('');
    }

    /**
     * Создание записей с обновлением ProgressBar.
     *
     * @param  string  $modelClass
     * @param  int  $count
     * @param  string  $tableName
     * @param  ProgressBar  $progressBar
     * @return void
     */
    private function seedWithProgress(string $modelClass, int $count, string $tableName, ProgressBar $progressBar): void
    {
        $modelClass::factory($count)->create();
        $progressBar->advance($count);
        $this->printProgress($tableName, $count);
    }

    /**
     * Создание сотрудников с ролями и обновлением ProgressBar.
     *
     * @param  int  $count
     * @param  string  $tableName
     * @param  ProgressBar  $progressBar
     * @return void
     */
    private function seedEmployeesWithRoles(int $count, string $tableName, ProgressBar $progressBar): void
    {
        Employee::factory()->createWithRoles($count);
        $progressBar->advance($count);
        $this->printProgress($tableName, $count);
    }

    /**
     * Вывод прогресса для таблицы.
     *
     * @param  string  $tableName
     * @param  int  $count
     * @return void
     */
    private function printProgress(string $tableName, int $count): void
    {
        $output = new ConsoleOutput();
        $output->writeln("[$tableName] $count records added.");
    }
}
