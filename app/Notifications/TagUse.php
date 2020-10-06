<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TagUse extends Notification
{
    use Queueable;

    protected $from_model, $from_model_id;

    /**
     * Create a new notification instance.
     *
     * @param string $from_model
     * @param integer $from_model_id
     */
    public function __construct(string $from_model, int $from_model_id)
    {
        $this->from_model = $from_model;
        $this->from_model_id = $from_model_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'user_avatar' => auth()->user()->avatar,
            'from_model' => $this->from_model,
            'from_model_id' => $this->from_model_id,
        ];
    }
}
