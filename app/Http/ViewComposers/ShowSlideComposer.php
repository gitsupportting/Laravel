<?php


namespace App\Http\ViewComposers;


use App\Models\Course;
use App\Views\LessonView;
use Illuminate\View\View;

class ShowSlideComposer
{
    /**
     * @param View $view
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $slide = $view->getData()['slide'];

        $key = 'lesson_answers_' . $slide->lesson->id . '';
        $answers = session($key, []);

        $view->with(compact('slide', 'answers'));
    }
}
