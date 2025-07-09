<?php

namespace App\Actions\Rooms;

use App\DTOs\RoomDto;
use App\Repositories\roomRepository;

class CreateRoomAction
{
    public function __construct(protected RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function execute($request)
    {
        $data = RoomDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->roomRepository->create($data);
        if ($model) {
            return $model;
        } else {
            throw new \Exception('Failed to create department');
        }
        
    }
}
