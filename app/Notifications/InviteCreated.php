<?php

namespace App\Notifications;

use App\Enums\QueueNamesEnum;
use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteCreated extends Notification implements ShouldQueue
{
    use Queueable;


    public function __construct(protected $invite){}

    public function viaQueues()
    {
        return [
            'mail' => QueueNamesEnum::Notifications->value,
        ];
    }



    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('Account Invite')
        ->line('You have been invited to join our platform. Please accept the invitation to complete account creation.')
        ->action('Accept Invitation', url('/api/v1/accept-invite?invite_token=' . $this->invite->token))
        ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
