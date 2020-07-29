<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.users.create', [
            'entity' => new User()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        abort_if(auth()->user()->is_editor, Response::HTTP_FORBIDDEN);

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = Role::getAdminRole()->id;
        $data['email_verified_at'] = now();
        $data['is_editor'] = $request->input('is_editor');

        User::create($data);

        flash('Administrator was successfully created')->success();

        return redirect(route('home', ['anchor' => 'settings']));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $user->password = '';

        return  view('admin.users.edit', [
            'entity' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        abort_if(auth()->user()->is_editor, Response::HTTP_FORBIDDEN);

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'confirmed'
        ]);

        $data = $request->all();
        if(!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_editor'] = $request->input('is_editor');

        $user->update($data);

        flash('Administrator was successfully updates')->success();

        return redirect(route('home', ['anchor' => 'settings']));
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        abort_if(auth()->user()->is_editor, Response::HTTP_FORBIDDEN);
        $user->forceDelete();

        flash('Administrator was successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'settings']));
    }

    public function bulkDelete(Request $request)
    {
        abort_if(auth()->user()->is_editor, Response::HTTP_FORBIDDEN);
        $this->validate($request, [
            'ids' => 'required'
        ], [
            'ids.required' => 'No administrators selected'
        ]);

        User::whereIn('id', $request->input('ids'))
            ->forceDelete();

        flash('Selected administrators were successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'settings']));
    }
}
