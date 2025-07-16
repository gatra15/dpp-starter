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
use App\Actions\LogAction; // Import LogAction

class ApproveBookingAction
{
    use HandlesAuthorization;
    public function __construct(protected BookingRepository $bookingRepository, protected StatusRepository $statusRepository, protected LogAction $logAction)
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

            $pendingStatus = $this->statusRepository->customQuery('pending');
            $pimpinanApprovedStatus = $this->statusRepository->customQuery('pimpinan_approved');
            $approvedStatus = $this->statusRepository->customQuery('approved');
            $rejectedStatus = $this->statusRepository->customQuery('rejected'); // Juga ambil rejected untuk pengecekan

            if (!$pendingStatus || !$pimpinanApprovedStatus || !$approvedStatus || !$rejectedStatus) {
                throw new \Exception('Status "pending", "pimpinan_approved", atau "approved" tidak ditemukan di database.');
            }

            // Aturan transisi status (dari diskusi sebelumnya)
            if ($booking->status_id === $rejectedStatus->id) {
                throw new \Exception('Booking ini sudah ditolak dan tidak dapat disetujui.');
            }
            if ($booking->status_id === $approvedStatus->id) {
                throw new \Exception('Booking ini sudah disetujui dan tidak dapat disetujui lagi.');
            }

            if ($booking->status_id === $pendingStatus->id) {
                $booking->status_id = $pimpinanApprovedStatus->id;
                $booking->save();

                DB::commit();

                // Log aktivitas: Pimpinan menyetujui booking (tahap 1)
                $this->logAction->execute([
                    'model' => 'bookings',
                    'model_id' => $booking->id,
                    'action' => 'approved_stage_1', // Aksi spesifik untuk persetujuan pimpinan
                    'user_id' => auth()->id() // User yang melakukan approve
                ]);

                $hrUsers = User::role('HR')->get();
                if ($hrUsers->isEmpty()) {
                    Log::warning("Peringatan: Tidak ada user dengan role 'HR' untuk mengirim notifikasi persetujuan HR.");
                } else {
                    foreach ($hrUsers as $hrUser) {
                        // $hrUser->notify(new HRApproval($booking)); // Notifikasi ke HR
                    }
                }
                return $booking;

            } elseif ($booking->status_id === $pimpinanApprovedStatus->id && auth()->user()->hasRole('HR') && auth()->user()->hasRole('pimpinan')) {
                $booking->status_id = $approvedStatus->id;
                $booking->save();

                DB::commit();

                // Log aktivitas: HR menyetujui booking (tahap 2, final)
                $this->logAction->execute([
                    'model' => 'bookings',
                    'model_id' => $booking->id,
                    'action' => 'approved_stage_2', // Aksi spesifik untuk persetujuan HR
                    'user_id' => auth()->id() // User yang melakukan approve
                ]);

                // $booking->user->notify(new BookingApproved($booking)); // Notifikasi ke peminjam
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