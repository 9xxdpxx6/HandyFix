<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\MockObject\Rule\InvocationOrder;

class OrderService
{
    public function store($data)
    {
        $order = new Order();

        try {

            DB::beginTransaction();

            // Создание заказа
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'status_id' => $data['status_id'],
                'info' => $data['info'],
                'user_id' => 1,
                'date' => $data['date'],
                'total' => array_sum(array_map(function ($purchase) {
                    return $purchase['material_price'] + $purchase['service_price'];
                }, $data['purchases'])),
            ]);

            // Сохранение позиций
            if (isset($data['purchases'])) {
                foreach ($data['purchases'] as $purchaseData) {
                    $order->purchases()->create([
                        'name' => $purchaseData['name'],
                        'material_price' => $purchaseData['material_price'],
                        'service_price' => $purchaseData['service_price'],
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();
            abort(500);

        }

        return $order;
    }

    public function update($data, Order $order)
    {
        try {

            DB::beginTransaction();

            $order->update([
                'customer_id' => $data['customer_id'],
                'status_id' => $data['status_id'],
                'info' => $data['info'],
                'date' => $data['date'],
                'total' => array_sum(array_map(function ($purchase) {
                    return $purchase['material_price'] + $purchase['service_price'];
                }, $data['purchases'])),
            ]);

            if (isset($data['purchases'])) {
                $existingPurchaseIds = $order->purchases()->pluck('id')->toArray();
                $requestPurchaseIds = array_column($data['purchases'], 'id');
                $purchasesToDelete = array_diff($existingPurchaseIds, $requestPurchaseIds);
                $order->purchases()->whereIn('id', $purchasesToDelete)->delete();

                foreach ($data['purchases'] as $purchaseData) {
                    if (isset($purchaseData['id'])) {
                        // Обновление существующей покупки
                        $order->purchases()->where('id', $purchaseData['id'])->update([
                            'name' => $purchaseData['name'],
                            'material_price' => $purchaseData['material_price'],
                            'service_price' => $purchaseData['service_price'],
                        ]);
                    } else {
                        // Создание новой покупки
                        $order->purchases()->create([
                            'name' => $purchaseData['name'],
                            'material_price' => $purchaseData['material_price'],
                            'service_price' => $purchaseData['service_price'],
                        ]);
                    }
                }
            }

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();
            abort(500);

        }

        return $order;
    }

    public function delete(Order $order)
    {
        try {

            DB::beginTransaction();

            $order->purchases()->delete();
            $order->delete();

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();
            abort(500);

        }
    }
}
