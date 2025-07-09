<?php

namespace App\Actions\Roles;

use App\Repositories\RoleRepository;

class GetOptionAction
{
    public function __construct(
        protected RoleRepository $roleRepository,
    ) {
        //
    }

    public function execute()
    {
        return $this->roleRepository->getAll()->select('id', 'name')->get();
    }
}
