<?php


namespace App\Views;


use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LessonView
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Lesson
     */
    private $lesson;
    /**
     * @var Collection
     */
    private $courseLessons;

    public function __construct(User $user, Lesson $lesson, Collection $courseLessons)
    {
        $this->user = $user;
        $this->lesson = $lesson;
        $this->courseLessons = $courseLessons;
    }

    public function getId(): int
    {
        return $this->lesson->id;
    }

    public function getName(): string
    {
        return $this->lesson->name;
    }

    public function getDescription(): string
    {
        return $this->lesson->description;
    }

    public function isFinished(): bool
    {
        return !empty($this->getFinishDate());
    }

    public function canBeStarted(): bool
    {
        return !$this->isFinished() && $this->isPreviousLessonFinished();
    }

    public function getFinishDate(): ?Carbon
    {
        $student = $this->lesson->students->filter(function (User $student) {
            return $student->id == $this->user->id;
        })->first();

        return $student instanceof User ? Carbon::parse($student->pivot->completed_at) : null;
    }

    public function getFaIcon(): string
    {
        if ($this->isFinished()) {
            $icon = 'fa-check';
        } elseif ($this->canBeStarted()) {
            $icon = 'fa-play';
        } else {
            $icon = 'fa-lock';
        }

        return $icon;
    }

    private function isPreviousLessonFinished(): bool
    {
        if (!$prevLesson = $this->getPrevLesson()) {
            return true;
        }

        return $prevLesson->isFinished();
    }

    public function getPrevLesson(): ?LessonView
    {
        foreach ($this->courseLessons as $i => $lesson) {
            if (isset($this->courseLessons[$i + 1]) && $this->courseLessons[$i + 1]->id == $this->lesson->id) {
                return new LessonView($this->user, $lesson, $this->courseLessons);
            }
        }

        return null;
    }

    public function getNextLesson(): ?LessonView
    {
        foreach ($this->courseLessons as $i => $lesson) {
            if (isset($this->courseLessons[$i + 1]) && $this->courseLessons[$i]->id == $this->lesson->id) {
                return new LessonView($this->user, $this->courseLessons[$i + 1], $this->courseLessons);
            }
        }

        return null;
    }

    public function getAllQuestions(): array
    {
        $questions = [];
        foreach ($this->lesson->slides as $slide) {
            if ($slide->type == 'json' && $slide->validAnswer >= 0) {
                $questions[$slide->id] = $slide->answers[$slide->validAnswer];
            }
        }

        return $questions;
    }

    public function getAllAnswers(): array
    {
        $student = $this->lesson->students->where('id', $this->user->id)->first();
        if(!$student) {
            return  [];
        }
        return (array) json_decode($student->pivot->answers, true);
    }

    public function countValidAnswers(): int
    {
        $validAnswersQty = 0;
        $answers = $this->getAllAnswers();
        foreach ($this->getAllQuestions() as $slideId => $validAnswer) {
            if(array_key_exists($slideId, $answers) && $validAnswer == $answers[$slideId]) {
                $validAnswersQty++;
            }
        }

        return  $validAnswersQty;
    }
}
