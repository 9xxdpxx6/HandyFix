<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEntry extends Model
{
    use HasFactory;

    protected $table = 'service_entries';
    protected $guarded = false;
    protected $casts = [
        'id' => 'integer',
        'day_ledger_id' => 'integer',
        'service_type_id' => 'integer',
        'price' => 'float',
        'description' => 'string',
    ];

    public function dayLedger()
    {
        return $this->belongsTo(DayLedger::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

}
