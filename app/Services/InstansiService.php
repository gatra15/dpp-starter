<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Instansi\GetOptionAction;
use App\Actions\Instansi\GetInstansiAction;
use App\Actions\Instansi\CreateInstansiAction;
use App\Actions\Instansi\DeleteInstansiAction;
use App\Actions\Instansi\UpdateInstansiAction;
use App\Actions\Instansi\GetDetailInstansiAction;

class InstansiService extends BaseService
{
    public function __construct(
        GetInstansiAction $getInstansiAction,
        GetDetailInstansiAction $detailAction,
        GetOptionAction $getOptionAction,
        CreateInstansiAction $createAction,
        UpdateInstansiAction $updateAction,
        DeleteInstansiAction $deleteAction
    ) {
        parent::__construct('instansi', $getInstansiAction, $detailAction, $getOptionAction, $createAction, $updateAction, $deleteAction);
    }
}
