<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayLedger extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'day_ledgers';
    protected $guarded = false;
    protected $casts = [
        'id' => 'integer',
        'date' => 'date',
        'total' => 'float',
        'balance' => 'float',
    ];

    public function materialEntries()
    {
        return $this->hasMany(MaterialEntry::class);
    }

    public function serviceEntries()
    {
        return $this->hasMany(ServiceEntry::class);
    }

    public function expenseEntries()
    {
        return $this->hasMany(ExpenseEntry::class);
    }

    public function getMaterialsIncomingAttribute()
    {
        return $this->materialEntries->sum('incoming');
    }

    public function getServicesTotalAttribute()
    {
        return $this->serviceEntries->sum('price');
    }

    public function getExpensesTotalAttribute()
    {
        return $this->expenseEntries->sum('price');
    }
}
