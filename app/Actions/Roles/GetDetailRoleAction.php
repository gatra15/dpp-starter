<?php

namespace App\Actions\Roles;

use App\Repositories\RoleRepository;

class GetDetailRoleAction
{
    public function __construct(protected RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function execute($id)
    {
        return $this->roleRepository->show($id);
    }
}
