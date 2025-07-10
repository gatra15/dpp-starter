<?php

namespace App\Actions\Bookings;

use App\Repositories\BookingRepository;

class GetDetailBookingAction
{
    public function __construct(protected BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function execute($id)
    {
        return $this->bookingRepository->show($id);
    }
}
