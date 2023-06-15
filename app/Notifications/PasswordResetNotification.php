<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user)
    {
        //
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
            ->subject('Password Anda di ' . config('app.name') . ' telah berubah')
            ->line('Halo ' . $this->user->name .'!')
            ->line('Password Anda telah diubah oleh Sistem atau Administrator Sekolah. Silahkan hubungi Administrator Sekolah untuk informasi selanjutnya.')
            ->line('Jika Anda tidak merasa meminta hal ini, segera lakukan proses Forgot Password manual.')
            ->action('Masuk', route('login'))
            ->line('Salam');
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
