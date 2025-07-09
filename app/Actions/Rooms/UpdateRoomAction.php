<?php

namespace App\Actions\Rooms;

use App\Repositories\RoomRepository;
use App\DTOs\RoomDto;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateRoomAction
{
    public function __construct(protected RoomRepository $roomRepository)
    {
    }

    public function execute(int $id, Request $request): Room
    {
        $room = $this->roomRepository->show($id);

        $data = RoomDto::fromRequest($request);
        $roomData = $data->toArray();
        $facilityIds = $data->facility_ids;

        $this->roomRepository->update($id, $roomData);

        if ($request->has('facility_ids')) {
            $room->facilities()->sync($facilityIds);
        }

        return $this->roomRepository->show($id);
    }
}