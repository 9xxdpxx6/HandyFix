<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Customer\CustomerMinResource;
use App\Http\Resources\Status\StatusResource;
use App\Http\Resources\User\UserMinResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderMinResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'user_id' => $this->user_id,

            'status' => new StatusResource($this->status),
        ];
    }
}
