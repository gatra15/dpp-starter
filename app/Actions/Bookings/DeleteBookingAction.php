<?php

namespace App\Actions\Bookings;

use App\Repositories\BookingRepository;

class DeleteBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }
    public function execute($id)
    {
        return $this->bookingRepository->delete($id);
    }
}