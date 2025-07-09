<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Rooms\GetRoomAction;
use App\Actions\Rooms\CreateRoomAction;
use App\Actions\Rooms\DeleteRoomAction;
use App\Actions\Rooms\UpdateRoomAction;
use App\Actions\Rooms\GetDetailRoomAction;

class RoomService extends BaseService
{
    public function __construct(
        GetRoomAction $getRoomAction,
        GetDetailRoomAction $detailAction,
        CreateRoomAction $createAction,
        UpdateRoomAction $updateAction,
        DeleteRoomAction $deleteAction
    ) {
        parent::__construct('rooms', $getRoomAction, $detailAction, $createAction, $updateAction, $deleteAction);
    }
}
