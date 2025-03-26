<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductPrice;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // Создаем запись в истории цен при создании товара
        ProductPrice::create([
            'product_id' => $product->id,
            'price' => $product->price
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Проверяем, изменилась ли цена
        if ($product->isDirty('price') || $product->wasChanged('price')) {
            // Создаем новую запись в истории цен
            ProductPrice::create([
                'product_id' => $product->id,
                'price' => $product->price
            ]);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
