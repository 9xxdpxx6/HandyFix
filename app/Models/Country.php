<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'countries';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'code',
        'name'
    ];

    public function brandsRegistered()
    {
        return $this->hasMany(Brand::class, 'registration_country_code');
    }

    public function brandsProduced()
    {
        return $this->hasMany(Brand::class, 'production_country_code');
    }
}
