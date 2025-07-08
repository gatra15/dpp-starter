<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function getAll(Request $request): Builder
    {
        return $this->model->query();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }
}
