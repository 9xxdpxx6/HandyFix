<?php

namespace App\Http\Resources\DayLedger;

use App\Http\Resources\User\UserMinResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DayLedgerResource extends JsonResource
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
            'balance' => $this->balance,
            'materials_incoming' => $this->materials_incoming,
            'services_total' => $this->services_total,
            'expenses_total' => $this->expenses_total,

            'user' => new UserMinResource($this->user),

            'materials' => MaterialEntryResource::collection($this->materialEntries),
            'services' => ServiceEntryResource::collection($this->serviceEntries),
            'expenses' => ExpenseEntryResource::collection($this->expenseEntries),
        ];
    }
}
