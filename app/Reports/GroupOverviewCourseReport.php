<?php

namespace App\Reports;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GroupOverviewCourseReport
{
    private $organizations;
    private $type;

    /**
     * @param  Collection $organizations
     * @return GroupOverviewCourseReport
     */
    public function whereOrganizations(Collection $organizations)
    {
        $this->organizations = $organizations;
        return $this;
    }

    /**
     * @param  string $type
     * @return GroupOverviewCourseReport
     */
    public function whereType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        $courses = DB::table('course_assignee_history')
            ->select('users.organization_id')
            ->selectRaw('MAX(completed_at) as completed_at')
            ->join('courses', 'course_assignee_history.course_id', 'courses.id')
            ->join('users', 'course_assignee_history.user_id', 'users.id')
            ->where('status', Course::STATUS_LIVE)
            ->whereNotNull('users.organization_id')
            ->groupBy('users.organization_id', 'user_id', 'course_id');

        if ($this->organizations) {
            $courses->whereIn('users.organization_id', $this->organizations->pluck('id'));
        }

        if ($this->type) {
            $courses->where('type', $this->type);
        }

        $builder = Course::query()->selectRaw('organization_id, ROUND(COUNT(completed_at) / COUNT(organization_id) * 100,0) as percent')
            ->fromSub($courses, 'history')
            ->withTrashed()
            ->groupBy('organization_id');

        return $builder->get()->keyBy('organization_id');
    }
}
