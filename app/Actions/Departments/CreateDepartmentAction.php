<?php

namespace App\Actions\Departments;

use App\DTOs\DepartmentDto;
use App\Repositories\DepartmentRepository;

class CreateDepartmentAction
{
    public function __construct(protected DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function execute($request)
    {
        $data = DepartmentDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->departmentRepository->create($data);
        if ($model) {
            return $model;
        } else {
            throw new \Exception('Failed to create department');
        }
    }
}
