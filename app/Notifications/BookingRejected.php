<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingRejected extends Notification
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

        return (new MailMessage)
            ->subject("Booking Ruangan Anda Ditolak: " . $title)
            ->greeting("Halo {$notifiable->name},")
            ->line("Booking Anda untuk ruangan **{$roomName}** dengan judul **{$title}**")
            ->line("pada tanggal {$startTime} - {$endTime} **telah ditolak**.")
            ->line('Mohon hubungi admin untuk informasi lebih lanjut.')
            ->action('Lihat Detail Booking', url('/user/my-bookings/' . $this->booking->id)); // Ganti URL ini
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
