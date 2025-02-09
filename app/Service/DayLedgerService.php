<?php

namespace App\Service;

use App\Models\DayLedger;
use Illuminate\Support\Facades\DB;

class DayLedgerService
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store($data)
    {
        $dayLedger = new DayLedger();

        try {
            DB::beginTransaction();

            // Создание новой записи в DayLedger
            $dayLedger = DayLedger::create([
                'user_id' => 1,
                'date' => $data['date'],
                'total' => $data['total'],
                'balance' => $data['balance'],
            ]);

            // Сохранение материалов
            if (isset($data['materials'])) {
                foreach ($data['materials'] as $materialData) {
                    $dayLedger->materialEntries()->create([
                        'incoming' => $materialData['incoming'],
                        'issuance' => $materialData['issuance'],
                        'waste' => $materialData['waste'],
                        'balance' => $materialData['balance'],
                        'description' => $materialData['description'],
                    ]);
                }
            }

            // Сохранение услуг
            if (isset($data['services'])) {
                foreach ($data['services'] as $serviceData) {
                    $dayLedger->serviceEntries()->create([
                        'service_type_id' => $serviceData['service_type_id'],
                        'description' => $serviceData['description'],
                        'price' => $serviceData['price'],
                    ]);
                }
            }

            // Сохранение расходов
            if (isset($data['expenses'])) {
                foreach ($data['expenses'] as $expenseData) {
                    $dayLedger->expenseEntries()->create([
                        'description' => $expenseData['description'],
                        'price' => $expenseData['price'],
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }

        return $dayLedger;
    }

    public function update($data, DayLedger $dayLedger)
    {
        try {
            DB::beginTransaction();

            // Обновление записи в DayLedger
            $dayLedger->update([
                'user_id' => 1,
                'date' => $data['date'],
                'total' => $data['total'],
                'balance' => $data['balance'],
            ]);

            // Обновление материалов
            if (isset($data['materials'])) {
                $this->updateMaterials($dayLedger, $data['materials']);
            } else {
                $this->deleteAllMaterials($dayLedger);
            }

            // Обновление услуг
            if (isset($data['services'])) {
                $this->updateServices($dayLedger, $data['services']);
            } else {
                $this->deleteAllServices($dayLedger);
            }

            // Обновление расходов
            if (isset($data['expenses'])) {
                $this->updateExpenses($dayLedger, $data['expenses']);
            } else {
                $this->deleteAllExpenses($dayLedger);
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
            abort(500);
        }

        return $dayLedger;
    }

    protected function updateMaterials(DayLedger $dayLedger, array $materials)
    {
        $existingMaterialIds = $dayLedger->materialEntries()->pluck('id')->toArray();
        $requestMaterialIds = array_column($materials, 'id');
        $materialsToDelete = array_diff($existingMaterialIds, $requestMaterialIds);

        // Удаление записей, которые не присутствуют в запросе
        if (!empty($materialsToDelete)) {
            $dayLedger->materialEntries()->whereIn('id', $materialsToDelete)->delete();
        }

        foreach ($materials as $materialData) {
            if (isset($materialData['id'])) {
                // Обновление существующей записи материала
                $dayLedger->materialEntries()->where('id', $materialData['id'])->update([
                    'incoming' => $materialData['incoming'],
                    'issuance' => $materialData['issuance'],
                    'waste' => $materialData['waste'],
                    'balance' => $materialData['balance'],
                    'description' => $materialData['description'],
                ]);
            } else {
                // Создание новой записи материала
                $dayLedger->materialEntries()->create([
                    'incoming' => $materialData['incoming'],
                    'issuance' => $materialData['issuance'],
                    'waste' => $materialData['waste'],
                    'balance' => $materialData['balance'],
                    'description' => $materialData['description'],
                ]);
            }
        }
    }

    protected function updateServices(DayLedger $dayLedger, array $services)
    {
        $existingServiceIds = $dayLedger->serviceEntries()->pluck('id')->toArray();
        $requestServiceIds = array_column($services, 'id');
        $servicesToDelete = array_diff($existingServiceIds, $requestServiceIds);

        // Удаление записей, которые не присутствуют в запросе
        if (!empty($servicesToDelete)) {
            $dayLedger->serviceEntries()->whereIn('id', $servicesToDelete)->delete();
        }

        foreach ($services as $serviceData) {
            if (isset($serviceData['id'])) {
                // Обновление существующей записи услуги
                $dayLedger->serviceEntries()->where('id', $serviceData['id'])->update([
                    'service_type_id' => $serviceData['service_type_id'],
                    'description' => $serviceData['description'],
                    'price' => $serviceData['price'],
                ]);
            } else {
                // Создание новой записи услуги
                $dayLedger->serviceEntries()->create([
                    'service_type_id' => $serviceData['service_type_id'],
                    'description' => $serviceData['description'],
                    'price' => $serviceData['price'],
                ]);
            }
        }
    }

    protected function updateExpenses(DayLedger $dayLedger, array $expenses)
    {
        $existingExpenseIds = $dayLedger->expenseEntries()->pluck('id')->toArray();
        $requestExpenseIds = array_column($expenses, 'id');
        $expensesToDelete = array_diff($existingExpenseIds, $requestExpenseIds);

        // Удаление записей, которые не присутствуют в запросе
        if (!empty($expensesToDelete)) {
            $dayLedger->expenseEntries()->whereIn('id', $expensesToDelete)->delete();
        }

        foreach ($expenses as $expenseData) {
            if (isset($expenseData['id'])) {
                // Обновление существующей записи расхода
                $dayLedger->expenseEntries()->where('id', $expenseData['id'])->update([
                    'description' => $expenseData['description'],
                    'price' => $expenseData['price'],
                ]);
            } else {
                // Создание новой записи расхода
                $dayLedger->expenseEntries()->create([
                    'description' => $expenseData['description'],
                    'price' => $expenseData['price'],
                ]);
            }
        }
    }

    protected function deleteAllMaterials(DayLedger $dayLedger)
    {
        $dayLedger->materialEntries()->delete();
    }

    protected function deleteAllServices(DayLedger $dayLedger)
    {
        $dayLedger->serviceEntries()->delete();
    }

    protected function deleteAllExpenses(DayLedger $dayLedger)
    {
        $dayLedger->expenseEntries()->delete();
    }

    public function delete(DayLedger $dayLedger)
    {
        try {
            DB::beginTransaction();

            // Удаление всех связанных записей
            $dayLedger->materialEntries()->delete();
            $dayLedger->serviceEntries()->delete();
            $dayLedger->expenseEntries()->delete();

            // Удаление самой записи DayLedger
            $dayLedger->delete();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }
}
