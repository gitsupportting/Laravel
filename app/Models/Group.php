<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string name
 * @method static Builder forAdmin()
 */
class Group extends Model
{
    protected $fillable = ['name'];

    public function scopeForAdmin(Builder $builder): Builder
    {
        return $builder->withCount('organizations')
            ->with('managers');
    }

    public function managers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_managers', 'group_id')
            ->withTimestamps();
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'group_organizations', 'group_id')
            ->withTimestamps();
    }
}
