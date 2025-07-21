<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Contracts\DepartmentRepositoryInterface;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $department)
    {
        parent::__construct($department);
    }

    public function getAll()
    {
        return $this->model->query()->with('head');
    }
}
