<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'service_prices';
    protected $fillable = [
        'service_id',
        'price',
        'effective_from'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
