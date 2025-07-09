<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Urusan\GetUrusanAction;
use App\Actions\Urusan\CreateUrusanAction;
use App\Actions\Urusan\DeleteUrusanAction;
use App\Actions\Urusan\UpdateUrusanAction;
use App\Actions\Urusan\GetDetailUrusanAction;

class UrusanService extends BaseService
{
    public function __construct(
        GetUrusanAction $getUrusanAction,
        GetDetailUrusanAction $detailAction,
        CreateUrusanAction $createAction,
        UpdateUrusanAction $updateAction,
        DeleteUrusanAction $deleteAction
    ) {
        parent::__construct('urusan', $getUrusanAction, $detailAction, null, $createAction, $updateAction, $deleteAction);
    }
}
