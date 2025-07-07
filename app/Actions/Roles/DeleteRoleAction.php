<?php

namespace App\Actions\Roles;

use App\Repositories\RoleRepository;



class DeleteRoleAction
{
    public function __construct(protected RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function execute($id)
    {
        return $this->roleRepository->delete($id);
    }
}
