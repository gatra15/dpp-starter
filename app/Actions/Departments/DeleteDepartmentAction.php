<?php

namespace App\Actions\Departments;

use App\Repositories\DepartmentRepository;

class DeleteDepartmentAction
{
    public function __construct(protected DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function execute($id)
    {
        return $this->departmentRepository->delete($id);
    }
}
