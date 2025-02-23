<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'employees';
    protected $fillable = [
        'user_id',
        'qualification_id',
        'specialization_id',
        'fixed_salary',
        'commission_rate',
        'seniority',
        'hire_date',
        'termination_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function serviceEntries()
    {
        return $this->hasMany(ServiceEntry::class, 'mechanic_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'manager_id');
    }
}
