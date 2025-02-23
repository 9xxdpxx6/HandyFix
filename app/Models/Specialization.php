<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'specializations';
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
