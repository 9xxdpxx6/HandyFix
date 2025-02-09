<?php

namespace App\Http\Resources\DayLedger;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseEntryResource extends JsonResource
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
        ];
    }
}
