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
    protected $guarded = false;
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'customer_id' => 'integer',
        'total' => 'float',
        'info' => 'string',
        'status_id' => 'integer',
        'date' => 'date',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
