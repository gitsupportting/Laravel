<?php

namespace App\Http\Controllers\Employee;

use App\Models\Course;
use App\Views\CourseView;
use App\Views\LessonView;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    public function show($id)
    {
        $course = \Auth::user()->courses()->where('course_id', $id)->firstOrFail();
        $courseLessons = $course->lessons()->with('students')->get();
        $lessons = [];
        foreach ($courseLessons as $lesson) {
            $lessons[] = new LessonView(\Auth::user(), $lesson, $courseLessons);
        }

        return view('employee.course', compact('course', 'lessons'));
    }

    public function resume(Course $course)
    {
        return redirect(route('course.show', $course));
    }

    public function finish($id)
    {
        /** @var Course $course */
        $course = \Auth::user()->courses()
            ->with(['lessons', 'lessons.slides'])
            ->where('course_id', $id)
            ->firstOrFail();

        if($lastLesson = $course->lessons->last()) {
            $lessonView = new LessonView(\Auth::user(), $lastLesson, $course->lessons);
            if($lessonView->canBeStarted() && !$lessonView->isFinished()) {
                $key = 'lesson_answers_' . $lastLesson->id;
                $lastLesson->students()->syncWithoutDetaching([
                    \Auth::id() => [
                        'completed_at' => now(),
                        'answers' => json_encode((array) session($key))
                    ]
                ]);
                session([$key => null]);
            }
        }

        $course->markComplete(
            Auth::user(),
            (new CourseView($course, \Auth::user()))->calculateScore()
        );

        return redirect(route('course.showResults', $course));
    }

    public function showResults($id)
    {
        $course = \Auth::user()->courses()
            ->where('course_id', $id)
            ->firstOrFail();

        abort_if(!$course->pivot->score, Response::HTTP_NOT_FOUND);

        $lessonsViews = [];
        $courseLessons = $course->lessons;
        foreach ($courseLessons as $lesson) {
            $lessonsViews[] = new LessonView(\Auth::user(), $lesson, $courseLessons);
        }

        return view('employee.course.results', compact('course', 'lessonsViews'));
    }

    public function showCertificate($id)
    {
        $user = \Auth::user();
        $course = $user->courses()
            ->where('course_id', $id)
            ->firstOrFail();

        abort_if(!$course->pivot->completed_at, Response::HTTP_NOT_FOUND);

        return view('employee.course.certificate', compact('course', 'user'));
    }

    public function managerView(Course $course)
    {
        if(!\Auth::user()->courses()->where('course_id', $course->id)->first()) {
            $course->addAssignee(Auth::user());
        }

        return redirect(route('course.show', $course));
    }

    public function markComplete(Course $course, Request $request)
    {
        abort_if(!$course->isExternal()
            || !$request->user()->courses()->where('course_id', $course->id)->first(),
        404);

        $course->markComplete($request->user());

        flash("Course \"$course->name\" was marked as complete");

        return redirect()->route('home');
    }

    public function retake($id)
    {
        $currentUser = \Auth::user();
        /** @var Course $course */
        $course = $currentUser->courses()->where('course_id', $id)->firstOrFail();
        abort_if(!$course->isCompletedBy($currentUser), 404);

        $course->addAssignee($currentUser);

        return redirect(route('course.show', $course));
    }
}
