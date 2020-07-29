<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrganizationStoreRequest;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.organizations.create', [
            'entity' => new User(),
            'manager' => new User(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(OrganizationStoreRequest $request)
    {
        $data = $request->managerData();
        $data['role_id'] = Role::getManagerRole()->id;
        $data['email_verified_at'] = now();

        /** @var User $manager */
        $manager = User::create($data);

        $manager->organization()->create($request->organizationData());

        flash('Organization was successfully created')->success();

        return redirect(route('home', ['anchor' => 'organizations']));
    }

    /**
     * @param Organization $organization
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Organization $organization)
    {
        $user = $organization->manager;
        $user->password = '';

        return  view('admin.organizations.edit', [
            'entity' => $organization,
            'manager' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(OrganizationStoreRequest $request, Organization $organization)
    {
        $manager = $organization->manager;

        $manager->update($request->managerData());
        $organization->update($request->organizationData());

        flash('Organization was successfully updated')->success();

        return redirect(route('home', ['anchor' => 'organizations']));
    }

    /**
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Organization $organization)
    {
        $manager = $organization->manager;
        $employees = $organization->employees;
        $organization->forceDelete();
        $manager->forceDelete();
        $employees->each(function($employee) {
            $employee->forceDelete();
            DB::table('course_user')
                ->where('user_id', $employee->id)
                ->delete();
            DB::table('lesson_user')
                ->where('user_id', $employee->id)
                ->delete();
        });

        flash('Organization was successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'settings']));
    }

    public function bulkDelete(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ], [
            'ids.required' => 'No organizations selected'
        ]);

        $organizations = Organization::whereIn('id', $request->input('ids'))->get();
        foreach ($organizations as $organization) {
            $manager = $organization->manager;
            $employees = $organization->employees;
            $organization->forceDelete();
            $manager->forceDelete();
            $employees->each(function ($employee) {
                $employee->forceDelete();
                DB::table('course_user')
                    ->where('user_id', $employee->id)
                    ->delete();
                DB::table('lesson_user')
                    ->where('user_id', $employee->id)
                    ->delete();
            });
        }


        flash('Selected organizations were successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'organizations']));
    }
}
