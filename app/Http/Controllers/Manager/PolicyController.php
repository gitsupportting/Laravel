<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('manager.policies.create', [
            'entity' => new Policy()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'date' => 'required',
            'name_on_policy' => 'required',
            'version' => 'required',
            'description' => 'required',
        ]);
        $data['organization_id'] = Auth::user()->managerOrganization()->id;
        $data['author_id'] = Auth::user()->id;
        $data['date'] = Carbon::createFromFormat('m/d/Y', $data['date']);

        Policy::create($data);

        flash('Policy was successfully created')->success();

        return redirect(route('home', ['anchor' => 'policies']));
    }

    /**
     * @param Policy $policy
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Policy $policy)
    {
        return  view('manager.policies.edit', [
            'entity' => $policy,
        ]);
    }

    /**
     * @param Policy $policy
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stats(Policy $policy)
    {
        return  view('manager.policies.stats', [
            'policy' => $policy,
            'employees' =>  User::byEmployeeOrganization(
                auth()->user()->managerOrganization(),
                User::WITH_MANAGER
            )->get(),
        ]);
    }

    /**
     * @param Request $request
     * @param Policy $policy
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Policy $policy)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'date' => 'required',
            'name_on_policy' => 'required',
            'version' => 'required',
            'description' => 'required',
        ]);

        $data['date'] = Carbon::createFromFormat('m/d/Y', $data['date']);

        $policy->update($data);

        flash('Policy was successfully updated')->success();

        return redirect(route('home', ['anchor' => 'policies']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Policy  $policies
     * @return \Illuminate\Http\Response
     */
    public function destroy(Policy $policy)
    {
        $policy->forceDelete();

        flash('Policy was successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'policies']));
    }

    public function bulkDelete(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ], [
            'ids.required' => 'No policies selected'
        ]);

        Policy::whereIn('id', $request->input('ids'))
            ->forceDelete();

        flash('Selected policies were successfully deleted')->success();

        return redirect(route('home'));
    }
}
