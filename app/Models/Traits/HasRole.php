<?php

namespace App\Models\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * @property Role role
 * @method static Builder groupManagers()
 */
trait HasRole
{
    public function setRole(Role $role)
    {
        $this->role_id = $role->getKey();
        return $this;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return  $this->role->isAdmin();
    }

    public function isPrimaryManager(): bool
    {
        $organization = Auth::user()->managerOrganization();

        if (!$organization) {
            return false;
        }

        return $this->exists && $this->isManager() && $organization->manager->is($this);
    }

    public function isManager(): bool
    {
        return  $this->role->isManager();
    }

    public function isEmployee(): bool
    {
        return  $this->role->isEmployee();
    }

    public function isGroupManager(): bool
    {
        return  $this->role->isGroupManager();
    }

    public function hasAnyRole(array $roles)
    {
        return $this->role->isOneOf($roles);
    }

    public function scopeEmployees(Builder $builder): Builder
    {
        return $builder->byRole(Role::EMPLOYEE_ROLE);
    }

    public function scopeManagers(Builder $builder): Builder
    {
        return $builder->byRole(Role::MANAGER_ROLE);
    }

    public function scopeAdmins(Builder $builder): Builder
    {
        return $builder->byRole(Role::ADMIN_ROLE);
    }

    public function scopeGroupManagers(Builder $builder): Builder
    {
        return $builder->byRole(Role::GROUP_MANAGER_ROLE);
    }

    public function scopeByRole(Builder $builder, string $role): Builder
    {
        return $builder->whereHas('role', function($query) use($role) {
            return $query->whereName($role);
        });
    }
}
