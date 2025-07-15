<?php

namespace App\Actions\Bookings;

use App\Models\Status;
use Illuminate\Support\Facades\DB;
use App\Repositories\StatusRepository;
use App\Repositories\BookingRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RejectBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository, protected StatusRepository $statusRepository) {}

    public function execute($bookingId)
    {
        DB::beginTransaction();
        try {
            $booking = $this->bookingRepository->show($bookingId);

            $ApprovedStatus = $this->statusRepository->customQuery('approved');

            if (!$booking) {
                throw new ModelNotFoundException("Booking dengan ID {$bookingId} tidak ditemukan.");
            }

            if ($booking->status_id === $ApprovedStatus->id) {
                throw new \Exception('Booking tidak dapat ditolak karena statusnya tidak valid.');
            }
            // $rejectedStatus = Status::where('name', 'rejected')->first();
            $rejectedStatus = $this->statusRepository->customQuery('rejected');

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
