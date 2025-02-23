<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyLevel extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'loyalty_levels';
    protected $fillable = [
        'name',
        'min_points',
        'discount'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
