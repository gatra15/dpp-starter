<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCreated extends Notification
{
    use Queueable;

    protected Booking $booking;
    protected User $pimpinan;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, User $pimpinan)
    {
        $this->booking = $booking;
        $this->pimpinan = $pimpinan;
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
            ->subject("Permintaan Booking Ruangan Baru: " . $this->booking->title)
            ->greeting("Halo Pimpinan {$this->pimpinan->name},")
            ->line("Ada permintaan booking ruangan baru:")
            ->line("Judul: **{$title}**")
            ->line("Ruangan: **{$roomName}**")
            ->line("Waktu: {$startTime} - {$endTime}")
            ->line("Oleh: {$userName}")
            ->action('Tinjau Booking', url('/admin/bookings/' . $this->booking->id)) // Ganti URL ini ke halaman detail booking admin Anda
            ->line('Mohon segera ditinjau dan disetujui/ditolak.');
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
