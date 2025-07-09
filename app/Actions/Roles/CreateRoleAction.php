<?php

namespace App\Actions\Roles;

use App\DTOs\RoleDto;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;

class CreateRoleAction
{
    public function __construct(protected RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();
        try {
            $data = RoleDto::fromRequest($request);
            $data = $data->toArray();
            $model = $this->roleRepository->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat role: ' . $e->getMessage(), 0, $e);
        }
    }
}
