<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Slide;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'course_id' => 'required|exists:courses,id'
        ]);

        return  view('admin.lessons.create', [
            'courseId' => $request->input('course_id'),
            'entity' => new Lesson()
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
            'course_id' => 'required|exists:courses,id',
            'name' => 'required',
            'description' => 'required',
        ]);

        Lesson::create($data);

        flash('Lesson was successfully created')->success();

        return redirect(route('courses.edit', $data['course_id']));
    }

    /**
     * @param Lesson $lesson
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Lesson $lesson)
    {
        return  view('admin.lessons.edit', [
            'courseId' => $lesson->course_id,
            'entity' => $lesson,
        ]);
    }

    /**
     * @param Request $request
     * @param Lesson $lesson
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Lesson $lesson)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $lesson->update($data);

        flash('Lesson was successfully updated')->success();

        return redirect(route('courses.edit', $lesson->course_id));
    }

    /**
     * @param Lesson $lesson
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Lesson $lesson)
    {
        $course = $lesson->course;
        $lesson->forceDelete();

        flash('Lesson was successfully deleted')->success();

        return redirect(route('courses.edit', $course));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDelete(Request $request)
    {
        Lesson::whereIn('id', $request->input('ids'))
            ->forceDelete();

        flash('Selected lessons were successfully deleted')->success();

        return back();
    }

    public function sortSlides(Request $request)
    {
        foreach ($request->input('slide') as $k => $id) {
            Slide::query()->where('id', $id)->update(['position' => $k+1]);
        }

        return response()->json(['success' => true]);
    }

    public function sortLessons(Request $request)
    {
        foreach ($request->input('lesson') as $k => $id) {
            Lesson::query()->where('id', $id)->update(['position' => $k+1]);
        }

        return response()->json(['success' => true]);
    }
}
