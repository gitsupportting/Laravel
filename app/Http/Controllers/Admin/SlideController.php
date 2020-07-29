<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationExceptionAppear error when add slide
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'lesson_id' => 'required|exists:lessons,id'
        ]);
        $lesson = Lesson::findOrFail($request->input('lesson_id'));
        $slide = new Slide();
        $slide->type = Slide::TYPE_JSON;

        return  view('admin.slides.create', [
            'lessonId' => $request->input('lesson_id'),
            'entity' => $slide,
            'lesson' => $lesson,
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
            'lesson_id' => 'required|exists:lessons,id',
            'name' => 'required',
            'type' => 'required',
            'content' => 'required',
        ]);

        $data['correct_answer_message'] = $request->input('correct_answer_message');
        $data['incorrect_answer_message'] = $request->input('incorrect_answer_message');
        if($data['type'] == Slide::TYPE_HTML) {
            $data['content'] = $request->input('content_html', '');
        } else {
            $data['content'] = json_encode($data['content']);
        }
        $data['bg_color'] = $request->input('bg_color');

        $data['video_url'] = (string) $request->input('video_url', '');

        /** @var Slide $slide */
        $slide = Slide::create($data);
        if($request->hasFile('image')) {
            $slide
                ->addMedia($request->file('image'))
                ->toMediaCollection();

            return redirect(route('slides.edit', [$slide, 'show_image_attachment' => 1]));
        }

        flash('Slide was successfully created')->success();

        return redirect(route('lessons.edit', $data['lesson_id']));
    }

    /**
     * @param Slide $slide
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Slide $slide)
    {
        return  view('admin.slides.edit', [
            'lesson' => $slide->lesson,
            'lessonId' => $slide->lesson_id,
            'entity' => $slide,
        ]);
    }

    /**
     * @param Request $request
     * @param Slide $slide
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Slide $slide)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'type' => 'required',
            'content' => 'required',
        ]);

        $data['correct_answer_message'] = (string) $request->input('correct_answer_message', '');
        $data['incorrect_answer_message'] = (string) $request->input('incorrect_answer_message', '');
        $data['video_url'] = (string) $request->input('video_url', '');
        $data['bg_color'] = $request->input('bg_color');
        $data['bg_image'] = $request->input('bg_image');

        if($data['type'] == Slide::TYPE_HTML) {
            $data['content'] = $request->input('content_html', '');
        } else {
            $data['content'] = json_encode($data['content']);
        }

        $slide->update($data);
        if($request->hasFile('image')) {
            if($slide->image) {
                $slide->image->delete();
            }
            $slide
                ->addMedia($request->file('image'))
                ->toMediaCollection();
        }

        flash('Slide was successfully updated')->success();

        if($request->has('upload_image')) {
            return redirect(route('slides.edit', [$slide, 'show_image_attachment' => 1]));
        }

        if($request->has('upload_video')) {
            return redirect(route('slides.edit', [$slide, 'show_video_attachment' => 1]));
        }

        return redirect(route('slides.edit', $slide));
    }

    /**
     * @param Slide $slide
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Slide $slide)
    {
        $lesson = $slide->lesson;
        $slide->forceDelete();

        flash('Slide was successfully deleted')->success();

        return redirect(route('lessons.edit', $lesson));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDelete(Request $request)
    {
        foreach ($request->input('ids') as $id) {
            if($slide = Slide::find($id)) {
                $slide->delete();
            }
        }

        flash('Selected slides were successfully deleted')->success();

        return back();
    }

    public function copy(Slide $slide)
    {
        $newSlide = $slide->replicate();
        $newSlide->name .= ' {cloned}';
        $newSlide->position = $slide->lesson->rawSlides()->orderBy('position', 'desc')->first()->position + 1;
        $newSlide->push();

        flash()->success('Slide was successfully cloned');

        return redirect(route('lessons.edit', $slide->lesson));
    }
}
