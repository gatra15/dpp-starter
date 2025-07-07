<?php

namespace App\Actions\Roles;

use App\DTOs\RoleDto;
use App\Repositories\RoleRepository;

class CreateRoleAction
{
    public function __construct(protected RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function execute($request)
    {
        $data = RoleDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->roleRepository->create($data);
        if ($model) {
            return $model;
        } else {
            throw new \Exception('Failed to create role');
        }
    }
}
