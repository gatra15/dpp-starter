<?php

namespace App\Services;

use App\Actions\Bookings\GetBookingAction;
use App\Actions\Bookings\GetDetailBookingAction;
use App\Actions\Bookings\CreateBookingAction;
use App\Actions\Bookings\UpdateBookingAction;
use App\Actions\Bookings\DeleteBookingAction;
use App\Actions\Bookings\ApproveBookingAction;
use App\Actions\Bookings\RejectBookingAction;

class BookingService extends BaseService
{
    protected ApproveBookingAction $approveBookingAction;
    protected RejectBookingAction $rejectBookingAction;

    public function __construct(
        GetBookingAction $getBookingAction,
        GetDetailBookingAction $getDetailBookingAction,
        CreateBookingAction $createAction,
        UpdateBookingAction $updateAction,
        DeleteBookingAction $deleteAction,
        ApproveBookingAction $approveBookingAction,
        RejectBookingAction $rejectBookingAction
    ) {
        parent::__construct(
            'bookings',
            $getBookingAction,
            $getDetailBookingAction,
            null,
            $createAction,
            $updateAction,
            $deleteAction
        );

        $this->approveBookingAction = $approveBookingAction;
        $this->rejectBookingAction = $rejectBookingAction;
    }

    public function approve($bookingId)
    {
        return $this->approveBookingAction->execute($bookingId);
    }

    public function reject($bookingId)
    {
        return $this->rejectBookingAction->execute($bookingId);
    }
}
