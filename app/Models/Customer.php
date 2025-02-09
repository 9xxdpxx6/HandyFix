<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Customer
 *
 * @method static Builder|Purchase where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Collection|Purchase[] all($columns = ['*'])
 * @method static Builder|Purchase query()
 * @mixin \Eloquent
 */

class Customer extends Model
{
    use HasFactory;
    use Filterable;
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'phone' => 'string',
        'info' => 'string',
    ];

    protected $table = 'customers';
    protected $guarded = false;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
