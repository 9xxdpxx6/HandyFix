<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\ProgressBar;
use App\Models\User;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Order;
use App\Models\ServiceEntry;
use App\Models\LoyaltyHistory;
use App\Models\Purchase;
use App\Models\ProductPrice;
use App\Models\ServicePrice;

class DatabaseSeeder extends Seeder
{
    private ConsoleOutput $output;
    private ProgressBar $progressBar;
    private int $totalRecords = 0;
    private array $seedingPlan = [];

    public function run()
    {
        $this->output = new ConsoleOutput();

        // Run the static seeders first
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
        $this->call(VehicleModelsSeeder::class);

        // Prepare dynamic seeding plan
        $this->prepareSeedingPlan([
            User::class => 200,
            Customer::class => 100,
            Employee::class => 20, // Special case for employees with roles
            Vehicle::class => 130,
            Order::class => 1000,
            ServiceEntry::class => 2000,
            LoyaltyHistory::class => 1000,
            Purchase::class => 2000,
            ProductPrice::class => 1000,
            ServicePrice::class => 1000
        ]);

        // Start seeding with a fancy progress bar
        $this->startFancyProgressBar();
        $this->executeSeeding();
        $this->finishProgressBar();
        
        // Обновляем total заказов после создания всех связанных записей
        $this->updateOrderTotals();
    }

    /**
     * Prepare the seeding plan and calculate total records
     *
     * @param array $plan
     * @return void
     */
    private function prepareSeedingPlan(array $plan): void
    {
        $this->seedingPlan = $plan;
        $this->totalRecords = array_sum($plan);

        $this->output->writeln("<info>Preparing to seed {$this->totalRecords} records across " . count($plan) . " models</info>");
        $this->output->writeln('');
    }

    /**
     * Configure and start a fancy progress bar
     *
     * @return void
     */
    private function startFancyProgressBar(): void
    {
        $this->progressBar = new ProgressBar($this->output, $this->totalRecords);

        // Customize the progress bar format to make it more dynamic
        ProgressBar::setFormatDefinition('custom', ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s% | %message%');
        $this->progressBar->setFormat('custom');
        $this->progressBar->setBarCharacter('<fg=green>▓</>');
        $this->progressBar->setEmptyBarCharacter('<fg=red>░</>');
        $this->progressBar->setProgressCharacter('<fg=green>▓</>');
        $this->progressBar->setMessage('Starting...');

        $this->progressBar->start();
    }

    /**
     * Execute all seeding operations
     *
     * @return void
     */
    private function executeSeeding(): void
    {
        foreach ($this->seedingPlan as $modelClass => $count) {
            $modelName = $this->getModelName($modelClass);
            $this->progressBar->setMessage("Seeding {$modelName}...");

            if ($modelClass === Employee::class) {
                $this->seedEmployeesWithRoles($count);
            } else {
                $this->seedModel($modelClass, $count);
            }

            // Small pause for visual effect
            usleep(200000); // 0.2 seconds
        }
    }

    /**
     * Seed a specific model
     *
     * @param string $modelClass
     * @param int $count
     * @return void
     */
    private function seedModel(string $modelClass, int $count): void
    {
        $chunkSize = min($count, 100); // Process in smaller chunks for better visual feedback
        $remaining = $count;

        while ($remaining > 0) {
            $currentChunk = min($remaining, $chunkSize);
            $modelClass::factory($currentChunk)->create();
            $this->progressBar->advance($currentChunk);
            $remaining -= $currentChunk;

            // Small pause between chunks for visual effect
            if ($remaining > 0) {
                usleep(50000); // 0.05 seconds
            }
        }

        $modelName = $this->getModelName($modelClass);
        $this->progressBar->setMessage("<info>{$modelName}:</info> {$count} records added");
    }

    /**
     * Seed employees with roles
     *
     * @param int $count
     * @return void
     */
    private function seedEmployeesWithRoles(int $count): void
    {
        $chunkSize = min($count, 10); // Smaller chunks for employee creation with roles
        $remaining = $count;

        while ($remaining > 0) {
            $currentChunk = min($remaining, $chunkSize);
            Employee::factory()->createWithRoles($currentChunk);
            $this->progressBar->advance($currentChunk);
            $remaining -= $currentChunk;

            if ($remaining > 0) {
                usleep(100000); // 0.1 seconds
            }
        }

        $this->progressBar->setMessage("<info>Employee:</info> {$count} records with roles added");
    }

    /**
     * Get the model name from its class
     *
     * @param string $modelClass
     * @return string
     */
    private function getModelName(string $modelClass): string
    {
        $parts = explode('\\', $modelClass);
        return end($parts);
    }

    /**
     * Finish the progress bar with a summary
     *
     * @return void
     */
    private function finishProgressBar(): void
    {
        $this->progressBar->finish();
        $this->output->writeln('');
        $this->output->writeln('');
        $this->output->writeln("<fg=green;options=bold>✓ Seeding completed: {$this->totalRecords} records created across " . count($this->seedingPlan) . " models</>");
        $this->output->writeln('');
    }

    /**
     * Обновляет total всех заказов на основе фактических сумм услуг и товаров
     *
     * @return void
     */
    private function updateOrderTotals(): void
    {
        $this->output->writeln('');
        $this->output->writeln("<info>Обновление сумм заказов...</info>");
        
        $progressBar = new ProgressBar($this->output, Order::count());
        $progressBar->setFormat('custom');
        $progressBar->start();
        
        Order::chunk(100, function ($orders) use ($progressBar) {
            foreach ($orders as $order) {
                // Считаем сумму услуг
                $servicesTotal = ServiceEntry::where('order_id', $order->id)
                    ->selectRaw('SUM(price * quantity) as total')
                    ->first()
                    ->total ?? 0;
                
                // Считаем сумму товаров
                $purchasesTotal = Purchase::where('order_id', $order->id)
                    ->selectRaw('SUM(price * quantity) as total')
                    ->first()
                    ->total ?? 0;
                
                // Обновляем total заказа
                $order->total = $servicesTotal + $purchasesTotal;
                $order->save();
                
                $progressBar->advance();
            }
        });
        
        $progressBar->finish();
        $this->output->writeln('');
        $this->output->writeln("<fg=green;options=bold>✓ Суммы заказов успешно обновлены</>");
    }
}
