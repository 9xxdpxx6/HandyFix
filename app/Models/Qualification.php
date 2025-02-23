<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'qualifications';
    protected $fillable = [
        'name',
        'min_seniority',
        'code',
        'description'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
