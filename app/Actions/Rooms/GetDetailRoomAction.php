<?php

namespace App\Actions\Rooms;

use App\Repositories\RoomRepository;

class GetDetailRoomAction
{
    public function __construct(protected RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }
    public function execute($id)
    {
        return $this->roomRepository->show($id);
    }
}
