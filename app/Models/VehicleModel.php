<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'vehicle_models';

    protected $fillable = [
        'brand_id',
        'name',
        'generation',
        'start_year',
        'end_year',
        'is_facelift',
        'facelift_year'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
