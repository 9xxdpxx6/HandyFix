<?php

namespace App\Observers;

use App\Models\Service;
use App\Models\ServicePrice;
use Carbon\Carbon;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        // Создаем запись в истории цен при создании услуги
        ServicePrice::create([
            'service_id' => $service->id,
            'price' => $service->price
        ]);
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        // Проверяем, изменилась ли цена
        if ($service->isDirty('price') || $service->wasChanged('price')) {
            // Создаем новую запись в истории цен
            ServicePrice::create([
                'service_id' => $service->id,
                'price' => $service->price
            ]);
        }
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     */
    public function forceDeleted(Service $service): void
    {
        //
    }
}
