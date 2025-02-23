<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'service_types';
    protected $fillable = [
        'name',
        'icon',
        'description'
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
