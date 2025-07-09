<?php

namespace App\Repositories;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use App\Contracts\RoomRepositoryInterface;

class RoomRepository extends BaseRepository implements RoomRepositoryInterface
{
    public function __construct(Room $room)
    {
        parent::__construct($room);
    }

    public function getAll(Request $request)
    {
        return $this->model->query();
    }
    public function show($id)
    {
        return $this->model->with('facilities')->find($id);
    }
}
