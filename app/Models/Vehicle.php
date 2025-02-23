<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'vehicles';
    protected $fillable = [
        'customer_id',
        'brand_id',
        'model',
        'year',
        'license_plate',
        'vin',
        'mileage'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
