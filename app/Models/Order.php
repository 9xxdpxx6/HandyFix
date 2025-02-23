<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'orders';
    protected $fillable = [
        'customer_id',
        'manager_id',
        'total',
        'comment',
        'note',
        'status_id',
        'completed_at'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function serviceEntries()
    {
        return $this->hasMany(ServiceEntry::class);
    }
}
