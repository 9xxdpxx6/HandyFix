<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Permission extends Component
{
    /**
     * Требуемое разрешение или массив разрешений
     *
     * @var string|array
     */
    public $requires;

    /**
     * Проверять все разрешения или любое из них
     *
     * @var bool
     */
    public $all;

    /**
     * Требуемая роль или массив ролей
     *
     * @var string|array
     */
    public $role;

    /**
     * Create a new component instance.
     *
     * @param string|array $requires Требуемое разрешение или массив разрешений
     * @param bool $all Проверять все разрешения (true) или любое из них (false)
     * @param string|array|null $role Требуемая роль или массив ролей
     * @return void
     */
    public function __construct($requires = null, $all = false, $role = null)
    {
        $this->requires = $requires;
        $this->all = $all;
        $this->role = $role;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return function (array $data) {
            // Если пользователь не авторизован, не показываем содержимое
            if (!Auth::check()) {
                return '';
            }

            $user = Auth::user();
            $hasPermission = true;
            $hasRole = true;

            // Проверяем разрешения, если указаны
            if ($this->requires) {
                $permissions = is_array($this->requires) ? $this->requires : [$this->requires];
                
                if ($this->all) {
                    // Проверяем наличие всех разрешений
                    $hasPermission = $user->hasAllPermissions($permissions);
                } else {
                    // Проверяем наличие любого из разрешений
                    $hasPermission = $user->hasAnyPermission($permissions);
                }
            }

            // Проверяем роли, если указаны
            if ($this->role) {
                $roles = is_array($this->role) ? $this->role : [$this->role];
                $hasRole = $user->hasAnyRole($roles);
            }

            // Отображаем содержимое, только если есть все требуемые права доступа
            return ($hasPermission && $hasRole) ? $data['slot'] : '';
        };
    }
} 