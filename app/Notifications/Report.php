<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Report extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    private $files;

    /**
     * @var string
     */
    private $title;

    /**
     * Report constructor.
     * @param string $title
     * @param array $files
     */
    public function __construct(string $title, array $files = [])
    {
        $this->files = $files;
        $this->title = $title;
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
        $message = (new MailMessage)
                    ->subject('Report ' . $this->title)
                    ->line('You email report is ready. Check it in your attachment')
                    ->line('Thank you for using Correct Care!');

        foreach ($this->files as $key => $file) {
            $date = date('Y-m-d');
            $message->attachData($file, "Report for $key $date.pdf");
        }

        return $message;
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
