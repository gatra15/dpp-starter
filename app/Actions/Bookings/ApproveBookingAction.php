<?php

namespace App\Actions\Bookings;

use App\Models\Status;
use App\Notifications\HRApproval;
use Illuminate\Support\Facades\DB;
use App\Notifications\BookingApproved;
use App\Repositories\BookingRepository;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class ApproveBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository) {}

    public function execute(int $bookingId): \App\Models\Booking
    {
        DB::beginTransaction();
        try {
            $booking = $this->bookingRepository->show($bookingId);

            if (!$booking) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Booking dengan ID {$bookingId} tidak ditemukan.");
            }

            $booking->load('user'); // Memastikan user yang membuat booking dimuat untuk notifikasi

            $pendingStatus = Status::where('name', 'pending')->first();
            $pimpinanApprovedStatus = Status::where('name', 'pimpinan_approved')->first();
            $approvedStatus = Status::where('name', 'approved')->first();

            if (!$pendingStatus || !$pimpinanApprovedStatus || !$approvedStatus) {
                throw new \Exception('Status "pending", "pimpinan_approved", atau "approved" tidak ditemukan di database.');
            }

            if ($booking->status_id === $pendingStatus->id) {
                $booking->status_id = $pimpinanApprovedStatus->id;
                $booking->save();

                DB::commit();

                $hrUsers = User::role('HR')->get();
                if ($hrUsers->isEmpty()) {
                    Log::warning("Peringatan: Tidak ada user dengan role 'HR' untuk mengirim notifikasi persetujuan HR.");
                } else {
                    foreach ($hrUsers as $hrUser) {
                        $hrUser->notify(new HRApproval($booking));
                    }
                }
                return $booking;
            } elseif ($booking->status_id === $pimpinanApprovedStatus->id) {
                $booking->status_id = $approvedStatus->id;
                $booking->save();

                DB::commit();

                $booking->user->notify(new BookingApproved($booking));
                return $booking;
            } else {
                throw new \Exception('Booking tidak dalam status "pending" atau "pimpinan_approved" untuk disetujui.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal menyetujui booking: ' . $e->getMessage(), 0, $e);
        }
    }
}
