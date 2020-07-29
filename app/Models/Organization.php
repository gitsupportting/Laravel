<?php

namespace App\Models;

use App\Exceptions\InvalidRoleTransition;
use App\Exceptions\OrganizationCapacityOverflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $license_capacity
 * @property Collection $allEmployees
 */
class Organization extends Model
{
    protected $fillable = ['name', 'notes', 'license_capacity', 'manger_id', 'created_at'];

    public function getOrganizationNameAttribute()
    {
        return $this->name;
    }

    public function addEmployee(User $user): User
    {
        if ($this->license_capacity && $this->allEmployees->count() >= $this->license_capacity) {
            throw OrganizationCapacityOverflow::onCreate($this);
        }

        /** @var User $employee */
        $employee = $this->employees()->save($user);
        $this->setEmployeeRole($employee, $employee->role);

        $this->allEmployees->push($user);
        return $user;
    }

    public function changeEmployeeRole(User $user, Role $role): User
    {
        if ($role->isAdmin()) {
            throw InvalidRoleTransition::forAdmin();
        }

        if ($user->isPrimaryManager()) {
            throw InvalidRoleTransition::forPrimaryManager();
        }

        return $this->setEmployeeRole($user, $role);
    }

    public function setEmployeeRole(User $user, Role $role): User
    {
        if ($role->isManager()) {
            $this->additionalManagers()->syncWithoutDetaching([$user->getKey()]);
            $user->organization_id = null;
        } else {
            $this->additionalManagers()->detach($user->getKey());
            $user->organization_id = $this->getKey();
        }

        $user->setRole($role);

        return $user;
    }

    public function restoreEmployees(array $users)
    {
        $total = $this->allEmployees->count() + count($users);
        if ($this->license_capacity && $total > $this->license_capacity) {
            throw OrganizationCapacityOverflow::onRestore($this);
        }
        $this->unsetRelation('allEmployees');
        return $this->employees()->whereIn('id', $users)->restore();
    }

    public function allEmployees(): HasMany
    {
        return $this->employees()->select('users.*')
            ->union($this->manager()->select('users.*'))
            ->union($this->additionalManagers()->select('users.*'));
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function additionalManagers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_managers', 'organization_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'organization_id')
            ->orderBy('first_name')
            ->orderBy('last_name');
    }

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}
