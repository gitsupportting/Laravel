<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GroupManagerController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function create(Group $group)
    {
        return  view('admin.groupManagers.create', [
            'entity' => new User(),
            'group' => $group,
        ]);
    }

    /**
     * @param  Group $group
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Group $group, Request $request)
    {
        $data = $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'password' => 'required|confirmed'
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = Role::getGroupManagerRole()->id;
        $user = User::create($data);
        $user->markEmailAsVerified();

        $user->groups()->attach($group->getKey());

        flash('Group Manager was successfully created')->success();

        return redirect()->route('groups.edit', $group);
    }

    /**
     * @param Group $group
     * @param User $manager
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Group $group, User $manager)
    {
        abort_if(!$manager->isGroupManager(), 403);

        return  view('admin.groupManagers.edit', [
            'group' => $group,
            'entity' => $manager
        ]);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @param User $manager
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $group, User $manager)
    {
        abort_if(!$manager->isGroupManager(), 403);

        $data = $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => ['required', 'email', Rule::unique(User::class)->ignoreModel($manager)],
            'phone' => 'nullable|string',
            'password' => 'confirmed'
        ]);

        if(!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $manager->update($data);

        flash('Group Manager was successfully updated')->success();

        return redirect()->route('groups.edit', $group);
    }

    /**
     * @param $group
     * @param User $manager
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($group, User $manager)
    {
        abort_if(!$manager->isGroupManager(), 403);

        $manager->forceDelete();

        flash('Group Manager was successfully deleted')->success();

        return redirect()->route('groups.edit', $group);
    }
}
