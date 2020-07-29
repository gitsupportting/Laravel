<?php

namespace App\Reports;

use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrganizationPolicyReport implements FromView, ShouldAutoSize
{
    private $organizations;
    private $status;

    /**
     * @param  Collection $organizations
     * @return self
     */
    public function whereOrganizations(Collection $organizations)
    {
        $this->organizations = $organizations->pluck('id');
        return $this;
    }

    /**
     * @param string $status
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
        $builder = User::query()->with(['policies' => function ($builder) {

            if ($this->status) {
                if ($this->status == 'complete') {
                    $builder->whereNotNull('policy_user.read_at');
                } else {
                    $builder->whereNull('policy_user.read_at');
                }
            }

                return $builder;
            }]);

        if ($this->organizations) {
            $builder->whereIn('organization_id', $this->organizations);
        }

        return $builder->get()->keyBy('id')->map(function ($user) {
            $data = $user->only('name');

            if ($user->policies->isEmpty()) {
                return false;
            }

            $data['items'] = [];
            foreach ($user->policies as $key => $policy) {
                $item = (object)[];
                $item->name = $policy->name;
                $item->description = $policy->pivot->read_at
                    ? 'Read on ' . Carbon::createFromFormat('Y-m-d H:i:s', $policy->pivot->read_at)->format('Y/m/d H:i:s')
                    : 'Not Read';
                $data['items'][$key] = $item;
            }

            return (object)$data;
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
