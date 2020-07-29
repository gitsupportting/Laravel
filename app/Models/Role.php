<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string name
 * @method static Builder managerManaged()
 */
class Role extends Model
{
    public CONST ADMIN_ROLE = 'admin';
    public CONST MANAGER_ROLE = 'manager';
    public CONST EMPLOYEE_ROLE = 'employee';
    public CONST GROUP_MANAGER_ROLE = 'group_manager';

    public function isAdmin(): bool
    {
        return $this->name == static::ADMIN_ROLE;
    }

    public function isManager(): bool
    {
        return $this->name == static::MANAGER_ROLE;
    }

    public function isGroupManager(): bool
    {
        return $this->name == static::GROUP_MANAGER_ROLE;
    }

    public function isEmployee(): bool
    {
        return $this->name == static::EMPLOYEE_ROLE;
    }

    public function isOneOf(array $roles): bool
    {
        return in_array($this->name, $roles);
    }

    public static function getAdminRole(): Role
    {
        return Role::whereName(Role::ADMIN_ROLE)->firstOrFail();
    }

    public static function getManagerRole(): Role
    {
        return Role::whereName(Role::MANAGER_ROLE)->firstOrFail();
    }

    public static function getGroupManagerRole(): Role
    {
        return Role::whereName(Role::GROUP_MANAGER_ROLE)->firstOrFail();
    }

    public static function getEmployeeRole(): Role
    {
        return Role::whereName(Role::EMPLOYEE_ROLE)->firstOrFail();
    }

    public static function scopeManagerManaged(Builder $builder)
    {
        return $builder->whereIn('name', [Role::EMPLOYEE_ROLE, Role::MANAGER_ROLE])->orderBy('name');
    }
}
