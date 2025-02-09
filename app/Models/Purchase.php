<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\Purchase
 *
 * @method static Builder|Purchase where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Collection|Purchase[] all($columns = ['*'])
 * @method static Builder|Purchase query()
 * @mixin \Eloquent
 */

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';
    protected $guarded = false;
    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'name' => 'string',
        'qty' => 'integer',
        'material_price' => 'float',
        'service_price' => 'float',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
