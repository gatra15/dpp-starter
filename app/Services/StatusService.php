<?php

namespace App\Services;

use App\Actions\Status\GetOptionAction;
use App\Actions\Status\GetStatusAction;
use App\Actions\Status\CreateStatusAction;
use App\Actions\Status\DeleteStatusAction;
use App\Actions\Status\UpdateStatusAction;
use App\Actions\Status\GetDetailStatusAction;

class StatusService extends BaseService
{
    protected GetOptionAction $getOptionAction;

    public function __construct(
        GetStatusAction $getStatusAction,
        CreateStatusAction $createAction,
        UpdateStatusAction $updateAction,
        DeleteStatusAction $deleteAction,
        GetOptionAction $getOptionAction,
        GetDetailStatusAction $detailAction

    ) {
        parent::__construct('status', $getStatusAction, $detailAction, $getOptionAction, $createAction, $updateAction, $deleteAction);
    }
}
