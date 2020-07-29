<?php

namespace App\Http\Controllers\Employee;

use App\Models\Policy;
use App\Http\Controllers\Controller;
use App\Models\User;

class PolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view,policy');
    }

    public function show(Policy $policy)
    {
        return view('employee.policy', compact('policy'));
    }

    public function read(Policy $policy)
    {
        /** @var User $user */
        $user = auth()->user();
        $user->policies()->syncWithoutDetaching([$policy->id => ['read_at' => now()]]);

        flash()->success('Policy was marked as read and understood.');
        return back();
    }
}
