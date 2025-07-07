<?php

namespace App\Actions\Users;
use App\Repositories\DepartmentRepository;

class UpdateDepartmentAction
{
    public function __construct(protected DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function execute($id, $data)
    {
        return $this->departmentRepository->update($id, $data);
    }

}