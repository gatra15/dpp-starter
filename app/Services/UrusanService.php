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
        protected GetUrusanAction $getUrusanAction,
        protected GetDetailUrusanAction $detailAction,
        protected CreateUrusanAction $createAction,
        protected UpdateUrusanAction $updateAction,
        protected DeleteUrusanAction $deleteAction
    ) {
        parent::__construct('urusan', $getUrusanAction, $detailAction, $createAction, $updateAction, $deleteAction);
    }
}
