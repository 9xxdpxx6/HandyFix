<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialEntry extends Model
{
    use HasFactory;

    protected $table = 'material_entries';
    protected $guarded = false;
    protected $casts = [
        'id' => 'integer',
        'day_ledger_id' => 'integer',
        'incoming' => 'float',
        'issuance' => 'float',
        'waste' => 'float',
        'balance' => 'float',
        'description' => 'string',
    ];

    public function dayLedger()
    {
        return $this->belongsTo(DayLedger::class);
    }
}
