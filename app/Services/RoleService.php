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
        GetOptionAction $getOptionAction,
        CreateRoleAction $createAction,
        UpdateRoleAction $updateAction,
        DeleteRoleAction $deleteAction
    ) {
        parent::__construct('roles', $getAction, $getOptionAction, $createAction, $updateAction, $deleteAction);
    }
}