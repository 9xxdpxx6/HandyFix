<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Обработка события создания заказа
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $this->handleOrderStatus($order);
    }

    /**
     * Обработка события обновления заказа
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        // Проверяем, изменился ли статус заказа
        if ($order->isDirty('status_id')) {
            $this->handleOrderStatus($order);
        }
    }

    /**
     * Обработка изменения статуса заказа
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    private function handleOrderStatus(Order $order)
    {
        // Получаем новый статус
        $status = $order->status;
        
        // Загружаем связанные покупки, если они еще не загружены
        if (!$order->relationLoaded('purchases')) {
            $order->load('purchases.product');
        }

        // Если статус закрывающий, но дата завершения не установлена
        if ($status->is_closing && !$order->completed_at) {
            // Устанавливаем дату завершения
            $order->completed_at = Carbon::now();
            $order->saveQuietly(); // Сохраняем без вызова событий обновления
            
            // Списываем товары со склада
            $this->deductProductsFromStock($order);
        } 
        // Если статус не закрывающий, но дата завершения установлена
        elseif (!$status->is_closing && $order->completed_at) {
            // Получаем предыдущий статус (если это обновление)
            $oldStatusId = $order->getOriginal('status_id');
            $oldStatus = null;
            
            if ($oldStatusId) {
                $oldStatus = \App\Models\Status::find($oldStatusId);
            }
            
            // Если предыдущий статус был закрывающим, возвращаем товары на склад
            if ($oldStatus && $oldStatus->is_closing) {
                $this->returnProductsToStock($order);
            }
            
            // Очищаем дату завершения
            $order->completed_at = null;
            $order->saveQuietly(); // Сохраняем без вызова событий обновления
        }
    }

    /**
     * Списание товаров со склада
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    private function deductProductsFromStock(Order $order)
    {
        foreach ($order->purchases as $purchase) {
            if ($purchase->product) {
                $product = $purchase->product;
                $newQuantity = $product->quantity - $purchase->quantity;
                
                // Проверяем, чтобы количество не стало отрицательным
                $product->quantity = max(0, $newQuantity);
                $product->save();
                
                Log::info("Списано {$purchase->quantity} ед. товара {$product->name} (ID: {$product->id}) при закрытии заказа №{$order->id}");
            }
        }
    }

    /**
     * Возврат товаров на склад
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    private function returnProductsToStock(Order $order)
    {
        foreach ($order->purchases as $purchase) {
            if ($purchase->product) {
                $product = $purchase->product;
                $product->quantity += $purchase->quantity;
                $product->save();
                
                Log::info("Возвращено {$purchase->quantity} ед. товара {$product->name} (ID: {$product->id}) при отмене закрытия заказа №{$order->id}");
            }
        }
    }
} 