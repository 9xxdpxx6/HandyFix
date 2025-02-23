<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'services';
    protected $fillable = [
        'service_type_id',
        'name',
        'description',
        'price'
    ];

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function serviceEntries()
    {
        return $this->hasMany(ServiceEntry::class);
    }

    public function servicePrices()
    {
        return $this->hasMany(ServicePrice::class);
    }
}
