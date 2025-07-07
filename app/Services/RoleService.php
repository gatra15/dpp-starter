<?php

namespace App\Services;

use App\Actions\Roles\GetRoleAction;
use App\Actions\Roles\CreateRoleAction;
use App\Actions\Roles\DeleteRoleAction;
use App\Actions\Roles\UpdateRoleAction;
use App\Actions\Roles\GetDetailRoleAction;

class RoleService extends BaseService
{
    public function __construct(
        GetRoleAction $getAction,
        GetDetailRoleAction $getDetailAction,
        CreateRoleAction $createAction,
        UpdateRoleAction $updateAction,
        DeleteRoleAction $deleteAction
    ) {
        parent::__construct('roles', $getAction, $getDetailAction, $createAction, $updateAction, $deleteAction);
    }
}