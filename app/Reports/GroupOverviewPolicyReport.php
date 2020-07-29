<?php

namespace App\Reports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class GroupOverviewPolicyReport
{
    private $organizations;

    /**
     * @param Collection $organizations
     * @return GroupOverviewPolicyReport
     */
    public function whereOrganizations(Collection $organizations)
    {
        $this->organizations = $organizations;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        $builder = DB::table('policy_user')
            ->selectRaw(
                'users.organization_id as id, ROUND(COUNT(read_at) / COUNT(user_id) * 100, 0) as percent'
            )->join('users', 'policy_user.user_id', 'users.id')
            ->groupBy('users.organization_id');

        if ($this->organizations) {
            $builder->whereIn('users.organization_id', $this->organizations->pluck('id'));
        }

        return $builder->get()->keyBy('id');
    }
}
