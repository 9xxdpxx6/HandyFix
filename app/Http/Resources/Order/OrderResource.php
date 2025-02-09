<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Customer\CustomerMinResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\User\UserMinResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'date' => $this->date,
            'total' => $this->total,
            'info' => $this->info,

            'user' => new UserMinResource($this->user),
            'customer' => new CustomerMinResource($this->customer),
            'status' => new StatusResource($this->status),

            'purchases' => PurchaseResource::collection($this->purchases),
        ];
    }
}
