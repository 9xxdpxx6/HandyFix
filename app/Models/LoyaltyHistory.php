<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyHistory extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'loyalty_history';
    protected $fillable = [
        'customer_id',
        'points',
        'action'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
