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
        'model_id',
        'year',
        'license_plate',
        'vin',
        'mileage'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
