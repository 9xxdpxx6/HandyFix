<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEntry extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'service_entries';
    protected $fillable = [
        'order_id',
        'service_id',
        'mechanic_id',
        'execution_date',
        'comment'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Employee::class, 'mechanic_id');
    }
}
