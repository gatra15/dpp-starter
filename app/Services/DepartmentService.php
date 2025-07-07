<?php

namespace App\Services;

use App\Services\BaseService;
use App\Actions\Users\UpdateDepartmentAction;
use App\Actions\Departments\GetDepartmentAction;
use App\Actions\Departments\CreateDepartmentAction;
use App\Actions\Departments\DeleteDepartmentAction;
use App\Actions\Departments\GetDetailDepartmentAction;

class DepartmentService extends BaseService
{
    public function __construct(
        protected GetDepartmentAction $getDepartmentAction,
        protected GetDetailDepartmentAction $detailAction,
        protected CreateDepartmentAction $createAction,
        protected UpdateDepartmentAction $updateAction,
        protected DeleteDepartmentAction $deleteAction
    ) {
        parent::__construct('departments', $getDepartmentAction, $detailAction, $createAction, $updateAction, $deleteAction);
    }
}
