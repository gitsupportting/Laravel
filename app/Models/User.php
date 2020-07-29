<?php

namespace App\Models;

use App\Models\Traits\HasRole;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property string first_name
 * @property string last_name
 * @property-read string name
 * @method static Builder|self organizationEmployees(bool $includeManagers)
 * @method static Builder|self byEmployeeOrganization(Organization $organization, bool $includeManagers)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasRole;

    const WITH_MANAGER = true;

    protected $with = ['role'];

    public $dates = ['date_of_birth', 'last_login_at'];

    /**
     * @var array
     */
    public static $formFields = [
        'first_name' => [
            'label' => 'First Name',
            'required' => true,
            'width' => 6,
        ],
        'last_name' => [
            'label' => 'Last Name',
            'required' => true,
            'width' => 6,
        ],
        'email' => [
            'label' => 'Email',
            'type' => 'email',
            'required' => true,
        ],
        'phone' => [
            'label' => 'Phone Number',
        ],
        'password' => [
            'label' => 'Password',
            'type' => 'password',
        ],
        'password_confirmation' => [
            'label' => 'Verify Password',
            'type' => 'password',
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'password', 'role_id', 'employee_id', 'job_role', 'username',
        'date_of_birth', 'last_login_at', 'organization_id', 'email_verified_at', 'is_editor', 'on_leave',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function generateUsername(string $firstName, string $lastName): string
    {
        $firstLetter = $firstName[0];
        $username = strtolower($firstLetter . $lastName);
        $i = 1;
        while(static::query()->byUsername($username)->exists()) {
            $username .= $i;
            $i++;
        }

        return  $username;
    }

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function organization()
    {
        return $this->hasOne(Organization::class, 'manager_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_managers');
    }

    public function scopeByUsername(Builder $builder, string $username): Builder
    {
        return $builder->whereUsername($username);
    }

    public function scopeByEmployeeOrganization(Builder $builder, Organization $organization, $includeManagers = false): Builder
    {
        $ids = $organization->additionalManagers->pluck('id');
        if($includeManagers) {
            $ids->push($organization->manager_id);
        }

        return $builder
            ->where(function($query) use($organization, $ids) {
                return $query
                    ->where('organization_id', $organization->id)
                    ->orWhereIn('id', $ids);
            });
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot(['score', 'completed_at', 'created_at'])
            ->active()
            ->orderByRaw(\DB::raw('CASE WHEN pivot_completed_at IS NULL THEN 0 ELSE 1 END, pivot_completed_at DESC, `course_user`.`created_at` desc'));
    }

    public function coursesHistory(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_assignee_history')
            ->withPivot(['score', 'completed_at', 'id', 'created_at',  'is_external'])
            ->active()
            ->withTrashed()
            ->orderByRaw(\DB::raw('CASE WHEN pivot_completed_at IS NULL THEN 1 ELSE 0 END, pivot_completed_at DESC, `course_assignee_history`.`created_at` desc'));
    }

    public function completedCoursesHistory()
    {
        return $this->belongsToMany(Course::class, 'course_assignee_history')
            ->withPivot(['score', 'completed_at',  'is_external'])
            ->active()
            ->wherePivot('completed_at', '!=', 'null')
            ->withTrashed()
            ->orderByRaw(\DB::raw('CASE WHEN pivot_completed_at IS NULL THEN 1 ELSE 0 END, pivot_completed_at DESC, `course_assignee_history`.`created_at` desc'));
    }

    public function completedCourses()
    {
        return $this->courses()
            ->withTrashed()
            ->wherePivot('completed_at', '!=', 'null');
    }

    public function incompleteCourses()
    {
        return $this->coursesHistory()
            ->distinct()
            ->withTrashed()
            ->groupBy('course_assignee_history.user_id', 'courses.id')
            ->whereHas('lessons.students', function ($builder) {
                return $builder->whereRaw('lesson_user.user_id  = course_assignee_history.user_id');
            })
            ->havingRaw('count(completed_at) = 0');
    }

    public function scopeOrganizationEmployees(Builder $builder, $includeManagers = false)
    {
        return User::query()
            ->byEmployeeOrganization($this->managerOrganization(), $includeManagers)
            ->orderBy('first_name')
            ->orderBy('last_name');
    }

    public function parentOrganization()
    {
        return $this->hasOne(Organization::class, 'id', 'organization_id');
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function getEmailAttribute($value)
    {
        return Str::substr($value, 0, 7) == 'generic' ? '' : $value;
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class, 'job_role');
    }

    public function additionalManagerForOrganization()
    {
        return $this->belongsToMany(Organization::class, 'organization_managers');
    }

    public function managerOrganization(): Organization
    {
        if($this->organization) {
            return  $this->organization;
        }

        return  $this->additionalManagerForOrganization->first();
    }

    public function groupManagerOrganizations(): Collection
    {
        return $this->loadMissing(['groups.organizations'])->groups->map(function ($group) {
            return $group->organizations;
        })->flatten()->keyBy('id');
    }

    public function policies()
    {
        return $this->belongsToMany(Policy::class)
            ->using(PolicyUserPivot::class)
            ->withPivot(['read_at']);
    }
}
