<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'customers';
    protected $fillable = [
        'user_id',
        'info',
        'loyalty_points',
        'loyalty_level_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loyaltyLevel()
    {
        return $this->belongsTo(LoyaltyLevel::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function loyaltyHistory()
    {
        return $this->hasMany(LoyaltyHistory::class);
    }
}
