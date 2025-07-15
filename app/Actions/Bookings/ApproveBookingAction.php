<?php

namespace App\Actions\Bookings;

use App\Models\User;
use App\Models\Status;
use App\Notifications\HRApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\BookingApproved;
use App\Repositories\StatusRepository;
use App\Repositories\BookingRepository;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ApproveBookingAction
{
    use HandlesAuthorization;
    public function __construct(protected BookingRepository $bookingRepository, protected StatusRepository $statusRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->statusRepository = $statusRepository;
    }

    public function execute(int $bookingId)
    {
        DB::beginTransaction();
        try {
            $booking = $this->bookingRepository->show($bookingId);

            if (!$booking) {
                throw new ModelNotFoundException("Booking dengan ID {$bookingId} tidak ditemukan.");
            }

            $booking->load('user');

            // $pendingStatus = Status::where('name', 'pending')->first();
            $pendingStatus = $this->statusRepository->customQuery('pending');
            $pimpinanApprovedStatus = $this->statusRepository->customQuery('pimpinan_approved');
            $approvedStatus = $this->statusRepository->customQuery('approved');

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
                        // $hrUser->notify(new HRApproval($booking));
                    }
                }
                return $booking;
            } elseif ($booking->status_id === $pimpinanApprovedStatus->id && auth()->user()->hasRole('HR') && auth()->user()->hasRole('pimpinan')) {
                $booking->status_id = $approvedStatus->id;
                $booking->save();

                DB::commit();

                // $booking->user->notify(new BookingApproved($booking));
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
