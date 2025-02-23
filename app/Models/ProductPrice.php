<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'product_prices';
    protected $fillable = [
        'product_id',
        'price',
        'effective_from'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
