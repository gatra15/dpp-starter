<?php

namespace App\Services;

use App\Actions\Roles\GetRoleAction;
use App\Actions\Roles\GetOptionAction;
use App\Actions\Roles\CreateRoleAction;
use App\Actions\Roles\DeleteRoleAction;
use App\Actions\Roles\UpdateRoleAction;

class RoleService extends BaseService
{
    public function __construct(
        GetRoleAction $getAction,
        CreateRoleAction $createAction,
        UpdateRoleAction $updateAction,
        DeleteRoleAction $deleteAction,
        GetOptionAction $getOptionAction,
    ) {
        parent::__construct('roles', $getAction, null, $getOptionAction, $createAction, $updateAction, $deleteAction);
    }
}