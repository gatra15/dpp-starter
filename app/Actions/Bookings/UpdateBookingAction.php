<?php

namespace App\Actions\Bookings;

use App\Repositories\BookingRepository;
use App\DTOs\BookingDto;
use App\Models\Booking;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class UpdateBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository) {}

    public function execute($id, $request)
    {
        DB::beginTransaction();

        try {
            $booking = $this->bookingRepository->show($id);

            if (!$booking) {
                throw new ModelNotFoundException("Booking dengan ID {$id} tidak ditemukan.");
            }

            $dto = BookingDto::fromRequest($request);

            $dataToUpdate = $request->only([
                'room_id',
                'title',
                'start_time',
                'end_time',
                'participants',
                'information',
                'status_id'
            ]);

            $startTime = isset($dataToUpdate['start_time']) ? Carbon::parse($dataToUpdate['start_time']) : $booking->start_time;
            $endTime = isset($dataToUpdate['end_time']) ? Carbon::parse($dataToUpdate['end_time']) : $booking->end_time;

            if ($endTime->lessThanOrEqualTo($startTime)) {
                throw ValidationException::withMessages([
                    'end_time' => 'Waktu selesai harus setelah waktu mulai.'
                ]);
            }

            $pendingStatus = Status::where('name', 'pending')->first();
            $approvedStatus = Status::where('name', 'approved')->first();
            $pimpinanApprovedStatus = Status::where('name', 'pimpinan_approved')->first();

            if (!$pendingStatus || !$approvedStatus || !$pimpinanApprovedStatus) {
                throw new \Exception('Satu atau lebih status default tidak ditemukan. Pastikan status default sudah ada di database.');
            }

            $existingBookings = Booking::where('room_id', $dataToUpdate['room_id'] ?? $booking->room_id)
                ->where('id', '!=', $id)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('start_time', [$startTime, $endTime->subSecond()])
                        ->orWhereBetween('end_time', [$startTime->addSecond(), $endTime])
                        ->orWhere(function ($query) use ($startTime, $endTime) {
                            $query->where('start_time', '<=', $startTime)
                                ->where('end_time', '>=', $endTime);
                        });
                })
                ->whereIn('status_id', [
                    $pendingStatus->id,
                    $approvedStatus->id,
                    $pimpinanApprovedStatus->id
                ])
                ->count();

            if ($existingBookings > 0) {
                throw ValidationException::withMessages([
                    'start_time' => 'Ruangan sudah di-booking untuk waktu yang diminta.',
                    'end_time' => 'Ruangan sudah di-booking untuk waktu yang diminta.'
                ]);
            }

            if (empty($dataToUpdate)) {
                throw new \Exception("Tidak ada data yang diberikan untuk memperbarui booking.");
            }

            $updated = $this->bookingRepository->update($id, $dataToUpdate);

            if (!$updated) {
                throw new \Exception("Gagal memperbarui booking dengan ID {$id}.");
            }

            $updatedBooking = $this->bookingRepository->show($id);

            DB::commit();

            return $updatedBooking;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui booking (kesalahan database): ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui booking: ' . $e->getMessage(), 0, $e);
        }
    }
}
