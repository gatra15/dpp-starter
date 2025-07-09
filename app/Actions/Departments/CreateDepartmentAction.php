<?php

namespace App\Actions\Departments;

use App\DTOs\DepartmentDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\DepartmentRepository;

class CreateDepartmentAction
{
    public function __construct(protected DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();

        try {
        $data = DepartmentDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->departmentRepository->create($data);
        DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat departemen: ' . $e->getMessage(), 0, $e);
        }
        
    }
}
