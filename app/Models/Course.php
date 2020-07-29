<?php

namespace App\Models;

use App\Views\CourseView;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Course
 * @package App\Models
 * @property-read Lesson[] lessons
 * @property-read User author
 * @property string name
 * @property string description
 * @property string status
 * @method static Builder byOrganization(User $user)
 */
class Course extends Model implements HasMedia
{

    use HasMediaTrait, SoftDeletes, HasImage {
        HasImage::registerMediaConversions insteadof HasMediaTrait;
    }
    const TYPE_INTERNAL = 'internal';
    const TYPE_EXTERNAL = 'external';

    protected $with = ['lessons'];

    /**
     * @var array
     */
    public static $formFields = [
        'name' => [
            'label' => 'Course Title',
            'required' => true,
        ],
        'bg_color' => [
            'label' => 'Card Color',
            'required' => true,
        ],
        'time_to_complete' => [
            'label' => 'Time to Complete',
            'required' => true,
        ],
        'description' => [
            'label' => 'Course Description',
            'required' => true,
            'type' => 'textarea',
        ],
        'status' => [
            'label' => 'Course Status',
            'type' => 'radio',
            'values' => self::STATUSES
        ],
    ];

    protected $fillable = ['bg_color', 'icon', 'name', 'description', 'status', 'created_by', 'duration', 'subjects', 'time_to_complete', 'type', 'organization_id'];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_LIVE = 'live';

    public const STATUSES = [self::STATUS_DRAFT => 'Draft', self::STATUS_LIVE => 'Live'];

    public function isExternal():bool
    {
        return  $this->type == self::TYPE_EXTERNAL;
    }

    public function isExternalHistory(): bool
    {
        return  $this->type == self::TYPE_EXTERNAL || optional($this->pivot)->is_external;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function getSlidesCountAttribute()
    {
        $slidesCount = 0;
        foreach ($this->lessons as $lesson) {
            $slidesCount += $lesson->slides->count();
        }

        return $slidesCount;
    }

    public function getEnrolledStudentsCountAttribute()
    {
        return $this->assignees()->count();
    }

    /**
     * @param $user
     * @param int $score
     * @param null $date
     * @param bool $isExternal
     */
    public function markComplete($user, $score = 0, $date = null, $isExternal = false)
    {
        $this->assignees()->sync([
            $user->getKey() => [
                'score' => $score,
                'completed_at' => $date ?? now()
            ]
        ]);
        $this->assigneesHistory()->syncWithoutDetaching([
            $user->getKey() => [
                'score' => $score,
                'completed_at' => $date ?? now(),
                'is_external' => $isExternal,
            ]
        ]);
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['score', 'created_at', 'completed_at'])
            ->withTimestamps();
    }

    public function assigneesHistory()
    {
        return $this->belongsToMany(User::class, 'course_assignee_history')
            ->withPivot(['score', 'completed_at', 'is_external'])
            ->withTimestamps();
    }

    public function graduates()
    {
        return $this->assignees()
            ->wherePivot('score', '>', 0);
    }

    public function isAssigned(User $user)
    {
        return $this->assignees->where('id', $user->id)->count();
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('status', self::STATUS_LIVE);
    }

    public function addAssignee(User $user)
    {
        $this->assignees()->detach($user->id);
        $this->assignees()->whereNull('completed_at')->detach($user->id);

        DB::table('lesson_user')
            ->where('user_id', $user->id)
            ->whereIn('lesson_id', $this->lessons->pluck('id'))
            ->delete();

        $this->assignees()->attach($user->id);
        $this->assigneesHistory()->attach($user->id);
    }

    public function isCompletedBy(User $user): bool
    {
        return (new CourseView($this, $user))->isFinished();
    }

    public function settings(): HasMany
    {
        return $this->hasMany(CourseSettings::class);
    }

    public function isDue(User $employee): bool
    {
        $assignee = $employee->courses->where('id', $this->id)->first();

        if(!$assignee) {
            return false;
        }

        if($employee->isManager()) {
            $organization = $employee->managerOrganization();
        } else {
            $organization = $employee->parentOrganization;
        }

        if(!$dueDate = $this->calculateDueDate($assignee->pivot->created_at, $organization)) {
            return  false;
        }

        return now() <= $dueDate;
    }

    public function isDueSoon(User $employee): bool
    {
        $assignee = $employee->coursesHistory->where('id', $this->id)->first();

        if(!$assignee) {
            return false;
        }

        if($employee->isManager()) {
            $organization = $employee->managerOrganization();
        } else {
            $organization = $employee->parentOrganization;
        }

        $assignDate = $assignee->pivot->created_at;
        if ($assignee->pivot->completed_at) {
            $assignDate = \Illuminate\Support\Carbon::parse($assignee->pivot->completed_at);
        }

        if(!$dueDate = $this->calculateDueDate(clone $assignDate, $organization)) {
            return  false;
        }

        $dueSoonDate = clone $dueDate;
        return now() <= $dueDate && now() >= $dueSoonDate->subMonth();
    }


    public function isDueToday(User $employee): bool
    {
        $assignee = $employee->coursesHistory->where('id', $this->id)->first();

        if(!$assignee) {
            return false;
        }

        if($employee->isManager()) {
            $organization = $employee->managerOrganization();
        } else {
            $organization = $employee->parentOrganization;
        }

        if(!$dueDate = $this->calculateDueDate($assignee->pivot->created_at, $organization)) {
            return  false;
        }

        return $dueDate->isToday();
    }


    public function isOverdue(User $employee): bool
    {
        $assignee = $employee->coursesHistory->where('id', $this->id)->first();

        if(!$assignee) {
            return false;
        }

        if($employee->isManager()) {
            $organization = $employee->managerOrganization();
        } else {
            $organization = $employee->parentOrganization;
        }

        $assignDate = $assignee->pivot->created_at;
        if ($assignee->pivot->completed_at) {
            $assignDate = \Illuminate\Support\Carbon::parse($assignee->pivot->completed_at);
        }

        if(!$overdueDate = $this->calculateDueDate($assignDate, $organization)) {
            return  false;
        }

        return now() > $overdueDate;
    }

    public function calculateDueDate(Carbon $assignDate, Organization $organization)
    {
        $settings = $this->settings
            ->where('organization_id', $organization->id)
            ->first();

        if(!$settings) {
            return  false;
        }

        return clone($assignDate)->addMonths($settings->settings['retake_month']);
    }

    public function props()
    {
        return $this->hasOne(CourseAttribute::class)->withDefault();
    }

    public function scopeForAdmin(Builder $builder): Builder
    {
        return $builder
            ->with('lessons', 'lessons.slides')
            ->whereType(self::TYPE_INTERNAL);
    }

    public function scopeForManager(Builder $builder, User $manager): Builder
    {
        return $builder
            ->with('assignees', 'graduates')
            ->byOrganization($manager)
            ->orderBy('name')
            ->active();
    }

    public function scopeByOrganization(Builder $builder, User $author): Builder
    {
        return $builder->where(function($query) use($author) {
            return $query
                ->where('type', self::TYPE_INTERNAL)
                ->orWhere(function($query) use($author) {
                    return $query
                        ->where('type', self::TYPE_EXTERNAL)
                        ->where('courses.organization_id', $author->managerOrganization()->id);
                });
        });
    }

    public function isCreatedBy(User $user): bool
    {
        return $this->created_by == $user->id;
    }

    public function getBgColorAttribute($value)
    {
        return !$this->isExternal() ? $value : '#F4F4F5';
    }
}
