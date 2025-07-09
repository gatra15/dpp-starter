<?php

namespace App\Services;

use App\Actions\Status\GetStatusAction;
use App\Actions\Status\CreateStatusAction;
use App\Actions\Status\DeleteStatusAction;
use App\Actions\Status\UpdateStatusAction;
use App\Actions\Status\GetOptionAction;

class StatusService extends BaseService
{
    protected GetOptionAction $getOptionAction;

    public function __construct(
        GetStatusAction $getStatusAction,
        CreateStatusAction $createAction,
        UpdateStatusAction $updateAction,
        DeleteStatusAction $deleteAction,
        GetOptionAction $getOptionAction
    ) {
        parent::__construct('status', $getStatusAction, null, $getOptionAction, $createAction, $updateAction, $deleteAction);
    }
}
