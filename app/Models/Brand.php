<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'brands';
    protected $fillable = [
        'name',
        'icon',
        'description',
        'is_original',
        'registration_country_code',
        'production_country_code'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function models()
    {
        return $this->hasMany(VehicleModel::class);
    }

    public function registrationCountry()
    {
        return $this->belongsTo(Country::class, 'registration_country_code');
    }

    public function productionCountry()
    {
        return $this->belongsTo(Country::class, 'production_country_code');
    }
}
