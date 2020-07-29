<?php


namespace App\Http\ViewComposers;

use App\Models\Course;
use App\Models\Organization;
use App\Models\EmailReport;
use App\Models\User;
use App\Reports\GroupOverviewCourseReport;
use App\Reports\GroupOverviewPolicyReport;
use App\Reports\OrganizationCourseReport;
use App\Reports\OrganizationPolicyReport;
use App\Reports\ReportFactory;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GroupManagerDashboardComposer
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $organizations = $this->request->user()->groupManagerOrganizations();
        $currentOrganization = $organizations->get($this->request->get('organization'), $organizations->first());

        $view->with([
            'currentOrganization' => optional($currentOrganization),
            'organizations' => $organizations,
            'chart' => $this->getChart($organizations),
            'report_types' => EmailReport::managerReports(),
            'reports' => $this->getReport($currentOrganization),
            'schedule_reports' => EmailReport::forManager($this->request->user())->get(),
        ]);
    }

    private function getChart(Collection $organizations): Collection
    {
        $chart = $this->request->get('chart');

        if ($chart == 'policy') {
            return (new GroupOverviewPolicyReport)
                ->whereOrganizations($organizations)
                ->getData();
        }

        return (new GroupOverviewCourseReport)
            ->whereOrganizations($organizations)
            ->whereType($chart == 'course-external' ? Course::TYPE_EXTERNAL : Course::TYPE_INTERNAL)
            ->getData();
    }

    private function getReport(?Organization $organization)
    {
        if (!$organization) {
            return collect();
        }

        return ReportFactory::fromDefinition(
            $this->request->get('report', 'completed_courses'),
            EmailReport::managerReports()
        )->whereOrganizations(collect([$organization]))->getData();
    }
}
