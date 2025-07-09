<?php

namespace App\Actions\Rooms;

use App\Repositories\RoomRepository;

class DeleteRoomAction
{
    public function __construct(protected RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function execute($id)
    {
        return $this->roomRepository->delete($id);
    }
}
