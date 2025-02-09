<?php

namespace App\Http\Resources\DayLedger;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialEntryResource extends JsonResource
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
            'incoming' => $this->incoming,
            'issuance' => $this->issuance,
            'waste' => $this->waste,
            'balance' => $this->balance,
        ];
    }
}
