<?php

namespace App\Actions\Bookings;

use App\Models\Status;
use Illuminate\Support\Facades\DB;
use App\Repositories\StatusRepository;
use App\Repositories\BookingRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Actions\LogAction; // Import LogAction
use App\Notifications\BookingRejectedNotification; // Import notifikasi jika digunakan
use App\Models\User; // Import User model jika digunakan untuk notifikasi

class RejectBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository, protected StatusRepository $statusRepository, protected LogAction $logAction) // Inject LogAction
    {}

    public function execute(int $bookingId): \App\Models\Booking
    {
        DB::beginTransaction();
        try {
            $booking = $this->bookingRepository->show($bookingId);

            if (!$booking) {
                throw new ModelNotFoundException("Booking dengan ID {$bookingId} tidak ditemukan.");
            }

            $booking->load('user');

            $pendingStatus = $this->statusRepository->customQuery('pending');
            $pimpinanApprovedStatus = $this->statusRepository->customQuery('pimpinan_approved');
            $approvedStatus = $this->statusRepository->customQuery('approved');
            $rejectedStatus = $this->statusRepository->customQuery('rejected');

            if (!$pendingStatus || !$pimpinanApprovedStatus || !$approvedStatus || !$rejectedStatus) {
                throw new \Exception('Satu atau lebih status default tidak ditemukan di database.');
            }

            if ($booking->status_id === $rejectedStatus->id) {
                throw new \Exception('Booking ini sudah ditolak.');
            }
            if ($booking->status_id === $approvedStatus->id) {
                throw new \Exception('Booking ini sudah disetujui dan tidak dapat ditolak.');
            }

            if ($booking->status_id === $pendingStatus->id || $booking->status_id === $pimpinanApprovedStatus->id) {
                $booking->status_id = $rejectedStatus->id;
                $booking->save();

                DB::commit();

                $this->logAction->execute([
                    'model' => 'bookings',
                    'model_id' => $booking->id,
                    'action' => 'rejected',
                    'user_id' => auth()->id()
                ]);


                return $booking;
            } else {
                throw new \Exception('Booking tidak dalam status "pending" atau "pimpinan_approved" untuk ditolak (saat ini ' . $booking->status->name . ').');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal menolak booking: ' . $e->getMessage(), 0, $e);
        }
    }
}
