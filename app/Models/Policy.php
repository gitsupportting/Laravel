<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Policy extends Model
{
    protected $fillable = [
        'name',
        'date',
        'name_on_policy',
        'version',
        'description',
        'organization_id',
        'author_id',
    ];

    public $dates = ['date'];

    /**
     * @var array
     */
    public static $formFields = [
        'name' => [
            'label' => 'Policy Title',
            'required' => true,
        ],
        'date' => [
            'label' => 'Date',
            'required' => true,
            'type' => 'date',
        ],
        'name_on_policy' => [
            'label' => 'Author/Lead',
            'required' => true,
        ],
        'version' => [
            'label' => 'Version',
            'required' => true,
            'type' => 'number',
        ],
        'description' => [
            'label' => 'Policy Text',
            'required' => true,
            'type' => 'textarea',
        ],
    ];

    public function jobRoles()
    {
        return $this->belongsToMany(JobRole::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class)
            ->using(PolicyUserPivot::class)
            ->withPivot(['read_at']);
    }

    public function graduates()
    {
        return $this->assignees()
            ->wherePivot('read_at', '!=', 'null');
    }

    public function isAssigned(User $user)
    {
        return $this->assignees->where('id', $user->id)->count();
    }

    public function addAssignee(User $user)
    {
        $this->assignees()->syncWithoutDetaching([$user->id]);
    }

    public function isReadBy(User $user): bool
    {
        return $this->graduates()->where('users.id', $user->id)->exists();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
