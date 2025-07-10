<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Contracts\BookingRepositoryInterface;
use App\Models\Booking;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function __construct(Booking $booking)
    {
        parent::__construct($booking);
    }

    public function getAll()
    {
        return $this->model->query();
    }

    public function show($id)
    {
        return $this->model->with(['user', 'room', 'status'])->find($id);
    }
}