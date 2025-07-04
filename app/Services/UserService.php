<?php

namespace App\Services;

use App\Actions\Users\GetUserAction;
use App\Actions\Users\CreateUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Actions\Users\DeleteUserAction;

class UserService extends BaseService
{
    protected $module;
    protected $getAction;
    protected $createAction;
    protected $updateAction;
    protected $deleteAction;

    public function __construct(
        GetUserAction $getAction,
        CreateUserAction $createAction,
        UpdateUserAction $updateAction,
        DeleteUserAction $deleteAction
    ) {
        parent::__construct('users', $getAction, null, $createAction, $updateAction, $deleteAction);
    }
}
