<?php

namespace App\Actions\Bookings;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Status;
use App\Models\Booking;
use App\DTOs\BookingDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\BookingCreated;
use App\Repositories\BookingRepository;


class CreateBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();

        try {
            $dto = BookingDto::fromRequest($request);
            $data = $dto->toArray();

            $data['user_id'] = $data['user_id'] ?? auth()->id();

            $pendingStatus = Status::where('name', 'pending')->first();
            $data['status_id'] = $pendingStatus->id;

            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);

            if ($endTime->lessThanOrEqualTo($startTime)) {
                throw new \Exception('Waktu tidak valid.');
            }

            $existingBookings = Booking::where('room_id', $data['room_id'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime->subSecond()])
                        ->orWhereBetween('end_time', [$startTime->addSecond(), $endTime])
                        ->orWhere(function ($query) use ($startTime, $endTime) {
                            $query->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                })
                ->whereIn('status_id', [
                    Status::where('name', 'pending')->first()->id,
                    Status::where('name', 'approved')->first()->id
                ])
                ->count();

            if ($existingBookings > 0) {
                throw new \Exception('Ruangan sudah di-booking untuk waktu yang diminta.');
            }

            $booking = $this->bookingRepository->create($data);

            if (!$booking) {
                throw new \Exception('Gagal membuat booking pada tahap penyimpanan.');
            }

            DB::commit();
            $pimpinanUsers = User::role('pimpinan')->get();

            if ($pimpinanUsers->isEmpty()) {
                Log::warning("Peringatan: Tidak ada user dengan role 'pimpinan' untuk mengirim notifikasi booking dibuat.");
            } else {
                foreach ($pimpinanUsers as $pimpinanUser) {
                    $pimpinanUser->notify(new BookingCreated($booking, $pimpinanUser));
                }
            }

            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat booking: ' . $e->getMessage(), 0, $e);
        }
    }
}
