<?php

namespace App\Services;

use App\Actions\Users\GetUserAction;
use App\Actions\Users\CreateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Actions\Users\GetDetailUserAction;

class UserService extends BaseService
{
    public function __construct(
        GetUserAction $getAction,
        GetDetailUserAction $detailAction,
        CreateUserAction $createAction,
        UpdateUserAction $updateAction,
        DeleteUserAction $deleteAction
    ) {
        parent::__construct('users', $getAction, $detailAction, $createAction, $updateAction, $deleteAction);
    }
}
