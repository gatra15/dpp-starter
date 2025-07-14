<?php

namespace App\Actions\Departments;

use App\Repositories\DepartmentRepository;
use App\DTOs\DepartmentDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UpdateDepartmentAction
{
    public function __construct(protected DepartmentRepository $departmentRepository) {}

    public function execute($id, $request)
    {
        DB::beginTransaction();

        try {
            $department = $this->departmentRepository->show($id);

            if (!$department) {
                throw new ModelNotFoundException("Department with ID {$id} not found.");
            }

            $dto = DepartmentDto::fromRequest($request);
            $departmentData = $dto->toArray();

            if (empty($departmentData)) {
                throw new \Exception("Tidak ada data yang diberikan untuk memperbarui departemen.");
            }

            $updated = $this->departmentRepository->update($id, $departmentData);

            if (!$updated) {
                throw new \Exception("Gagal memperbarui data departemen dengan ID {$id}.");
            }

            $updatedDepartment = $this->departmentRepository->show($id);

            DB::commit();

            return $updatedDepartment;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui departemen: ' . $e->getMessage(), 0, $e);
        }
    }
}
