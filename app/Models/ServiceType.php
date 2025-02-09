<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $table = 'service_types';
    protected $guarded = false;
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
    ];

    public function serviceEntries()
    {
        return $this->hasMany(ServiceEntry::class);
    }
}
