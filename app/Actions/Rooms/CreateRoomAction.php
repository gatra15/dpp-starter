<?php

namespace App\Actions\Rooms;

use App\DTOs\RoomDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\roomRepository;

class CreateRoomAction
{
    public function __construct(protected RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();

        try {
            $data = RoomDto::fromRequest($request);
            $data = $data->toArray();
            $model = $this->roomRepository->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat ruangan: ' . $e->getMessage(), 0, $e);
        }
    }
}
