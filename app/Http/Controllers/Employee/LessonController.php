<?php


namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Views\LessonView;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends Controller
{
    public function show(Lesson $lesson)
    {
        /** @var Course $course */
        $course = $lesson->course;
        $lessonView = new LessonView(\Auth::user(), $lesson, $course->lessons);
        if(!$lessonView->canBeStarted()) {
            abort(Response::HTTP_FORBIDDEN);
        }
        $nextLessonView = $lessonView->getNextLesson();
        $nextPageUrl = $nextLessonView
            ? route('lesson.saveResults', $lesson->id)
            : route('course.finish', $lesson->course);

        return view('employee.lesson', compact('lessonView', 'lesson', 'nextPageUrl'));
    }

    public function storeResults(Request $request, Lesson $lesson)
    {
        /** @var Course $course */
        $course = $lesson->course;
        $lessonView = new LessonView(\Auth::user(), $lesson, $course->lessons);
        if(!$lessonView->canBeStarted()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $key = 'lesson_answers_' . $lesson->id;
        $lesson->students()->syncWithoutDetaching([
            \Auth::id() => [
                'completed_at' => now(),
                'answers' => json_encode((array) session($key))
            ]
        ]);
        session([$key => null]);

        $nextLessonView = $lessonView->getNextLesson();

        $url = $nextLessonView
            ? route('lesson.show', $nextLessonView->getId())
            : route('course.finish', $lesson->course);

        return redirect($url);
    }

}
