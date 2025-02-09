<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseEntry extends Model
{
    use HasFactory;

    protected $table = 'expense_entries';
    protected $guarded = false;
    protected $casts = [
        'id' => 'integer',
        'day_ledger_id' => 'integer',
        'description' => 'string',
        'price' => 'float',
    ];

    public function dayLedger()
    {
        return $this->belongsTo(DayLedger::class);
    }
}
