<?php

namespace App\Actions\Bookings;

use App\Repositories\BookingRepository;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class RejectBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository)
    {
    }

    public function execute($bookingId)
    {
        DB::beginTransaction();
        try {
            $booking = $this->bookingRepository->show($bookingId);

            if (!$booking) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Booking dengan ID {$bookingId} tidak ditemukan.");
            }

            $rejectedStatus = Status::where('name', 'Rejected')->first();

            $booking->status_id = $rejectedStatus->id;
            $booking->save();

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal menolak booking: ' . $e->getMessage(), 0, $e);
        }
    }
}