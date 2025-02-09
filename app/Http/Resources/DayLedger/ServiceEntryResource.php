<?php

namespace App\Http\Resources\DayLedger;

use App\Http\Resources\ServiceType\ServiceTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day_ledger_id' => $this->day_ledger_id,
            'description' => $this->description,
            'price' => $this->price,
            'service_type_id' => $this->service_type_id,

//            'service_type' => new ServiceTypeResource($this->serviceType),
        ];
    }
}
