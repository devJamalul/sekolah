<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUser extends Notification
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
            ->subject('Konfirmasi Akun ' . config('app.name') . ' Anda')
            ->greeting('Halo, ' . $this->user->name . ' !')
            ->line('Anda terdaftar untuk sekolah ' . $this->user->school->school_name . '. Sebelumnya Anda harus mengkonfirmasi akun ini dengan menekan tombol di bawah ini:')
            ->action('Konfirmasi Akun', route('user-verification.index', [$this->user->email, $this->user->remember_token]))
            ->line('Terima kasih telah menggunakan aplikasi kami');
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
