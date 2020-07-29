<?php


namespace App\Views;


use App\Models\Course;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;

class CourseView
{
    /**
     * @var Course
     */
    private $course;

    /**
     * @var array
     */
    private $questions = [];

    /**
     * @var array
     */
    private $answers = [];
    /**
     * @var User
     */
    private $user;

    /**
     * @var LessonView[]
     */
    private $lessonsViews;

    /** @var Carbon */
    private $due;

    public function __construct(Course $course, User $user)
    {
        $this->course = $course;
        $this->user = $user;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function calculateScore()
    {
        if(!$this->hasQuestions()) {
            return 100;
        }

        if($this->countValidAnswers() == 0) {
            return 0.01;//mark course as completed with score 0.1 until get more info from client
        }

        return 100 * $this->countValidAnswers() / count($this->getAllQuestions());
    }

    public function isFinished(): bool
    {
        return !empty($this->course->pivot->completed_at);
    }

    public function getName(): string
    {
        return $this->course->name;
    }

    public function getScore(): float
    {
        return round($this->course->pivot->score);
    }

    public function getState(Organization $organization)
    {
        if ($this->course->isDueSoon($this->user)) {
            return 'Due Soon';
        } else if ($this->course->isOverdue($this->user)) {
            return 'Overdue';
        } else if ($this->isFinished()) {
            return 'Completed';
        }

        return 'Due';
    }

    public function getDueDate(Organization $organization)
    {
        $assignDate = $this->course->pivot->created_at;
        if ($this->course->pivot->completed_at) {
            $assignDate = \Illuminate\Support\Carbon::parse($this->course->pivot->completed_at);
        }

        $dueDate = $this->course->calculateDueDate($assignDate, $organization);
        return $dueDate ? $dueDate : now()->addYears(10);
    }

    public function getCompletedAt(): ?Carbon
    {
        return !empty($this->course->pivot->completed_at) ? Carbon::parse($this->course->pivot->completed_at) : null;
    }

    private function hasQuestions(): bool
    {
        return !empty($this->getAllQuestions());
    }

    private function getAllQuestions(): array
    {
        if (!$this->questions) {
            $questions = [];
            foreach ($this->getLessonsViews() as $lessonsView) {
                $questions += $lessonsView->getAllQuestions();
            }
            $this->questions = $questions;
        }

        return $this->questions;
    }

    private function getAllAnswers(): array
    {
        if ($this->answers) {
            $answers = [];
            foreach ($this->getLessonsViews() as $lessonView) {
                $answers += $lessonView->getAllAnswers();
            }
            $this->answers = $answers;
        }

        return $this->answers;
    }

    private function countValidAnswers(): int
    {
        $validAnswersQty = 0;
        foreach ($this->getLessonsViews() as $lessonView) {
            $validAnswersQty += $lessonView->countValidAnswers();
        }

        return $validAnswersQty;
    }

    private function getLessonsViews(): array
    {
        if (!$this->lessonsViews) {
            $lessonsViews = [];
            $courseLessons = $this->course->lessons;
            foreach ($courseLessons as $lesson) {
                $lessonsViews[] = new LessonView($this->user, $lesson, $courseLessons);
            }
            $this->lessonsViews = $lessonsViews;
        }

        return  $this->lessonsViews;
    }
}
