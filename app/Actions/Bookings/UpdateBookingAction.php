<?php

namespace App\Actions\Bookings;

use App\Repositories\BookingRepository;
use App\DTOs\BookingDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository)
    {
    }

    public function execute($id, $request)
    {
        $booking = $this->bookingRepository->show($id);

        if (!$booking) {
            throw new ModelNotFoundException("Booking dengan ID {$id} tidak ditemukan.");
        }

        $dto = BookingDto::fromRequest($request);
        $bookingData = $dto->toArray();

        $this->bookingRepository->update($id, $bookingData);

        return $this->bookingRepository->show($id);
    }
}