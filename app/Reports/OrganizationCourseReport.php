<?php

namespace App\Reports;

use App\Models\Course;
use App\Models\User;
use App\Views\CourseView;
use Dompdf\Dompdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrganizationCourseReport implements FromView, ShouldAutoSize
{
    private $organizations;
    private $type;
    private $status;

    /**
     * @param  Collection $organizations
     * @return self
     */
    public function whereOrganizations(Collection $organizations)
    {
        $this->organizations = $organizations;
        return $this;
    }

    /**
     * @param  string $type
     * @return self
     */
    public function whereType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param  string $status
     * @return self
     */
    public function whereStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        $builder = User::query()->with(['coursesHistory' => function ($builder) {
            if ($this->type) {
                $builder->where('type', $this->type);
            }

            return $builder;
        }, 'courses'])->orderBy('first_name');

        if ($this->organizations) {
            $builder->whereIn('organization_id', $this->organizations->pluck('id'));
        }

        return $builder->get()->map(function ($user) {
            $data = $user->only('name');

            $data['items'] = [];
            /**
             * @var  $key
             * @var Course $course
             */
            foreach ($user->coursesHistory->unique('id') as $key => $course) {
                $courseView = new CourseView($course, $user);

                if ($this->status && $this->status == 'complete') {
                    if($courseView->isFinished()) {
                        $data['items'][$key] = (object) [];
                        $data['items'][$key]->name = $courseView->getName();
                        $data['items'][$key]->description = 'Completed ' . $courseView->getCompletedAt()->format('d/m/Y');
                    }
                } elseif ($this->status && $this->status != 'complete') {
                    if(!$courseView->isFinished()) {
                        $data['items'][$key] = (object) [];
                        $data['items'][$key]->name = $courseView->getName();
                        $data['items'][$key]->description = $courseView->getState($this->organizations->first())
                            . ' '
                            . $courseView->getDueDate($this->organizations->first())->format('d/m/Y');
                    }
                }
            }

            if (empty($data['items'])) {
                return false;
            }

            return (object) $data;
        })->filter();
    }

    public function view(): View
    {
        return view('components.reports.orgranizationCourse')->with([
            'reports' => $this->getData(),
        ]);
    }

    public function toPdf()
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($this->view());
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        return $dompdf;
    }
}
