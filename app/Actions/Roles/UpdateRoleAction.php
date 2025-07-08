<?php

namespace App\Actions\Roles;

use App\Repositories\RoleRepository;

class UpdateRoleAction
{
    public function __construct(protected RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function execute($id, $request)
    {
        $data = $request->all();
        $this->roleRepository->update($id, $data);
        return $this->roleRepository->show($id);
    }
}