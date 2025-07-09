<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Departments\UpdateDepartmentAction;
use App\Actions\Departments\GetDepartmentAction;
use App\Actions\Departments\CreateDepartmentAction;
use App\Actions\Departments\DeleteDepartmentAction;
use App\Actions\Departments\GetDetailDepartmentAction;

class DepartmentService extends BaseService
{
    public function __construct(
        GetDepartmentAction $getDepartmentAction,
        GetDetailDepartmentAction $detailAction,
        CreateDepartmentAction $createAction,
        UpdateDepartmentAction $updateAction,
        DeleteDepartmentAction $deleteAction
    ) {
        parent::__construct('departments', $getDepartmentAction, $detailAction, null, $createAction, $updateAction, $deleteAction);
    }
}
