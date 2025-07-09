<?php

namespace App\Repositories;

use App\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function getAll()
    {
        return $this->model->query();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }
}
