<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class HRApproval extends Notification
{
    use Queueable;

    protected Booking $booking;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
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
        $roomName = $this->booking->room->name;
        $title = $this->booking->title;
        $startTime = $this->booking->start_time->format('d M Y H:i');
        $endTime = $this->booking->end_time->format('H:i');
        $userName = $this->booking->user->name;

        return (new MailMessage)
            ->subject("Perlu Persetujuan HR untuk Booking Ruangan: " . $title)
            ->greeting("Halo Tim HR {$notifiable->name},")
            ->line("Booking ruangan **{$roomName}** oleh {$userName} dengan judul **{$title}**")
            ->line("pada {$startTime} - {$endTime} telah disetujui oleh Pimpinan dan **menunggu persetujuan Anda**.")
            ->action('Tinjau Booking', url('/admin/bookings/' . $this->booking->id)) // Ganti URL ini
            ->line('Mohon segera ditinjau.');
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
