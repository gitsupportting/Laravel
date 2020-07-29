<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.groups.create', [
            'entity' => new Group(),
            'organizations' => Organization::with('manager', 'employees')->get(),
            'managers' => User::groupManagers()->get(),
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
            'name' => ['required', 'max:255'],
            'organizations.*' => [Rule::exists(Organization::class, 'id')],
            'managers.*' => [Rule::exists(User::class, 'id')],
        ]);

        /** @var Group $group */
        $group = Group::create($data);
        $group->organizations()->sync($request->input('organizations', []));

        flash('Group was successfully created')->success();

        return redirect(route('groups.edit', $group));
    }

    /**
     * @param Group $group
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Group $group)
    {
        return  view('admin.groups.edit', [
            'entity' => $group,
            'organizations' => Organization::with('manager', 'employees')->get(),
            'managers' => $group->managers,
        ]);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Group $group)
    {
        $data = $this->validate($request, [
            'name' => ['required', 'max:255'],
            'organizations.*' => [Rule::exists(Organization::class, 'id')],
            'managers.*' => [Rule::exists(User::class, 'id')],
        ]);

        $group->update($data);
        $group->organizations()->sync($request->input('organizations', []));

        flash('Group was successfully updated')->success();

        return redirect(route('home', ['anchor' => 'groups']));
    }

    /**
     * @param Group $group
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Group $group)
    {
        $group->forceDelete();

        flash('Group was successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'groups']));
    }

    public function bulkDelete(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ], [
            'ids.required' => 'No groups selected'
        ]);

        Group::destroy($request->input('ids'));

        flash('Selected groups were successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'groups']));
    }
}
