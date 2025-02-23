<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'statuses';
    protected $fillable = [
        'name',
        'color',
        'is_closing'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
