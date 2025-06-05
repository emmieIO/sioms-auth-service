<?php

namespace App\Notifications;

use App\Enums\QueueNamesEnum;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordQueued extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels and their queues.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'mail' => QueueNamesEnum::Notifications->value,
        ];
    }

}
