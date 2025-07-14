<?php

namespace App\Actions\Roles;

use App\DTOs\RoleDto; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateRoleAction
{
    public function __construct(protected RoleRepository $roleRepository) {}

    public function execute($id, $request)
    {
        DB::beginTransaction();

        try {
            $role = $this->roleRepository->show($id);

            if (!$role) {
                throw new ModelNotFoundException("Role dengan ID {$id} tidak ditemukan.");
            }

            $dto = RoleDto::fromRequest($request);
            $roleData = $dto->toArray();

            if (empty($roleData)) {
                throw new \Exception("Tidak ada data yang diberikan untuk memperbarui role.");
            }

            $updated = $this->roleRepository->update($id, $roleData);

            if (!$updated) {
                throw new \Exception("Gagal memperbarui data role dengan ID {$id}.");
            }

            $updatedRole = $this->roleRepository->show($id);

            DB::commit();

            return $updatedRole;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui role: ' . $e->getMessage(), 0, $e);
        }
    }
}
