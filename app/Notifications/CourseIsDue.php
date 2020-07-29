<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CourseIsDue extends Notification
{
    use Queueable;
    /**
     * @var User
     */
    private $employee;
    /**
     * @var Course
     */
    private $course;

    /**
     * CourseIsDue constructor.
     * @param User $employee
     * @param Course $course
     */
    public function __construct(User $employee, Course $course)
    {
        //
        $this->employee = $employee;
        $this->course = $course;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('You has assignment to a course "'.$this->course->name.'" which is due today.')
                    ->action('View course', route('course.show', $this->course))
                    ->line('Thank you for using Correct Care!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
