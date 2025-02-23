<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class Purchase extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'purchases';
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'quantity',
        'product_name'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
