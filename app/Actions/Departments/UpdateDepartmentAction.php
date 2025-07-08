<?php

namespace App\Actions\Departments;
use App\Repositories\DepartmentRepository;

class UpdateDepartmentAction
{
    public function __construct(protected DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function execute($id, $request)
    {
        $data = $request->all();
        $this->departmentRepository->update($id, $data);
        return $this->departmentRepository->show($id);
    }

}