<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.courses.create', [
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
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $data['created_by'] = Auth::id();

        $course = Course::create(array_merge($data, $request->only('bg_color', 'icon', 'duration', 'subjects', 'time_to_complete')));
        if($request->hasFile('image')) {
            $course
                ->addMedia($request->file('image'))
                ->toMediaCollection();
        }

        flash('Course was successfully created')->success();

        return redirect(route('home'));
    }

    /**
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Course $course)
    {
        return  view('admin.courses.edit', [
            'entity' => $course,
        ]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Course $course)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $course->update(array_merge($data, $request->only('bg_color', 'icon', 'duration', 'subjects', 'time_to_complete')));
        if($request->hasFile('image')) {
            if($course->image) {
                $course->image->delete();
            }
            $course
                ->addMedia($request->file('image'))
                ->toMediaCollection();
        }

        flash('Course was successfully updated')->success();

        return redirect(route('home'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $courses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->forceDelete();

        flash('Course was successfully deleted')->success();

        return redirect(route('home', ['anchor' => 'settings']));
    }

    public function bulkDelete(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ], [
            'ids.required' => 'No courses selected'
        ]);

        Course::whereIn('id', $request->input('ids'))
            ->forceDelete();

        flash('Selected courses were successfully deleted')->success();

        return redirect(route('home'));
    }
}
