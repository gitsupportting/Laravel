<?php

namespace App\Models;

use App\Notifications\Report;
use App\Reports\OrganizationCourseReport;
use App\Reports\OrganizationPolicyReport;
use App\Reports\ReportFactory;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

/**
 * @property string $command
 * @property string $email_to
 * @property Carbon $execute_at
 * @property Group $group
 * @method static Builder forManager(User $user)
 * @method static Builder dueNow()
 */
class EmailReport extends Model
{
    protected $fillable = ['command', 'email_to', 'execute_at'];

    protected $casts = ['execute_at' => 'datetime'];

    public static function managerReports()
    {
        return [
            'completed_courses' => [
                'title' => 'Completed Courses',
                'class' => OrganizationCourseReport::class,
                'definition' => [
                    'status' => 'complete',
                ]
            ],
            'uncompleted_courses' => [
                'title' => 'Uncompleted Courses',
                'class' => OrganizationCourseReport::class,
                'definition' => [
                    'status' => 'uncompleted',
                ]
            ],
            'completed_external' => [
                'title' => 'Completed External',
                'class' => OrganizationCourseReport::class,
                'definition' => [
                    'status' => 'complete',
                    'type' => Course::TYPE_EXTERNAL,
                ]
            ],
            'uncompleted_external' => [
                'title' => 'Uncompleted External',
                'class' => OrganizationCourseReport::class,
                'definition' => [
                    'status' => 'uncompleted',
                    'type' => Course::TYPE_EXTERNAL,
                ]
            ],
            'policy_read' => [
                'title' => 'Policies Read & Accepted',
                'class' => OrganizationPolicyReport::class,
                'definition' => [
                    'status' => 'complete',
                ]
            ],
            'policy_not_read' => [
                'title' => 'Policies Not Read',
                'class' => OrganizationPolicyReport::class,
                'definition' => [
                    'status' => 'uncompleted',
                ]
            ],
        ];
    }

    public function scopeDueNow(Builder $builder)
    {
        $now = Carbon::createFromDate(1, 1, 1)
            ->weekday(now()->weekday())
            ->setTimeFromTimeString(now()->toTimeString())
            ->setMinutes(0)
            ->setSeconds(0);

        return $builder->where('execute_at', $now)->with('group');
    }

    public function scopeForManager(Builder $builder, User $user)
    {
        return $builder->whereIn('group_id', $user->groups->pluck('id'));
    }

    public function send()
    {
        $files = [];

        foreach ($this->group->organizations as $organization) {
            /** @var Dompdf $content */
            $content = ReportFactory::fromDefinition($this->command, EmailReport::managerReports())
                ->whereOrganizations(collect([$organization]))->toPdf();
            $files[$organization->name] = $content->output();
        }

        Notification::route('mail', $this->email_to)->notify(
            new Report(EmailReport::managerReports()[$this->command]['title'], $files)
        );
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
