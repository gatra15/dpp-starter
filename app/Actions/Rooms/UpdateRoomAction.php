<?php

namespace App\Actions\Rooms;

use App\Repositories\RoomRepository;
use App\DTOs\RoomDto;
use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UpdateRoomAction
{
    public function __construct(protected RoomRepository $roomRepository)
    {
    }

    public function execute($id, $request)
    {
        DB::beginTransaction();

        try {
            $room = $this->roomRepository->show($id);

            if (!$room) {
                throw new ModelNotFoundException("Room with ID {$id} not found.");
            }

            $dto = RoomDto::fromRequest($request);
            $roomData = $dto->toArray();
            $facilityIds = $dto->facility_ids;

            if (!empty($roomData)) {
                $updated = $this->roomRepository->update($id, $roomData);
                if (!$updated) {
                    throw new \Exception("Gagal memperbarui data dasar ruangan dengan ID {$id}.");
                }
            }

            if ($request->has('facility_ids')) {
                $room->facilities()->sync($facilityIds);
            }

            $updatedRoom = $this->roomRepository->show($id);

            DB::commit();

            return $updatedRoom;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui ruangan: ' . $e->getMessage(), 0, $e);
        }
    }
}
