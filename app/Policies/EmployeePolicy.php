<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Определяет, может ли пользователь просматривать список сотрудников
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('read.employees');
    }

    /**
     * Определяет, может ли пользователь просматривать сотрудника
     */
    public function view(User $user, Employee $employee): bool
    {
        return $user->hasPermissionTo('read.employees');
    }

    /**
     * Определяет, может ли пользователь создавать сотрудников
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create.employees');
    }

    /**
     * Определяет, может ли пользователь обновлять сотрудника
     */
    public function update(User $user, Employee $employee): bool
    {
        return $user->hasPermissionTo('update.employees');
    }

    /**
     * Определяет, может ли пользователь удалять сотрудника
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasPermissionTo('delete.employees');
    }
} 