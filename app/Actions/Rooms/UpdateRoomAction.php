<?php

namespace App\Actions\Rooms;

use App\Repositories\RoomRepository;

class UpdateRoomAction
{
    public function __construct(protected RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function execute($id, $request)
    {
        $data = $request->all();
        $this->roomRepository->update($id, $data);
        return $this->roomRepository->show($id);
    }
}
