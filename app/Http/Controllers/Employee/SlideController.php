<?php


namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Slide;
use App\Views\LessonView;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SlideController extends Controller
{
    public function show(Slide $slide)
    {
        $lesson = $slide->lesson;
        /** @var Course $course */
        $course = $lesson->course;
        $lessonView = new LessonView(\Auth::user(), $lesson, $course->lessons);
        if(!$lessonView->canBeStarted()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $key = 'lesson_answers_' . $slide->lesson->id . '';
        $answers = session($key, []);
        return view('employee.slides.' . $slide->type, compact('slide', 'answers'));
    }

    public function storeAnswer(Request $request, Slide $slide)
    {
        $lesson = $slide->lesson;
        /** @var Course $course */
        $course = $lesson->course;
        $lessonView = new LessonView(\Auth::user(), $lesson, $course->lessons);
        if(!$lessonView->canBeStarted()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate($request, [
            'answer' => 'required'
        ]);

        $key = 'lesson_answers_' . $slide->lesson->id;
        $answers = session($key, []);
        $answers[$slide->id] = $request->input('answer');
        session([$key => $answers]);


        $response = ['success' => true];
        if($lesson->slides->last()->id == $slide->id) {
            $response['url'] = route('lesson.saveResults', $lesson);
        }

        return response()->json($response);
    }

    public function attachment(Request $request, $course, Slide $slide, $attachment)
    {
        $media = $slide->getMedia()->pop();
        if($media->file_name == $attachment) {
            return $media
                ->toResponse($request);
        }

        $media = $slide->getMedia('video')->pop();
        if($media->file_name == $attachment) {
            return $media
                ->toResponse($request);
        }

        abort(404);
    }
}
