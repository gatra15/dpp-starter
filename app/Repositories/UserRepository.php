<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getAll()
    {
        return $this->model->query()->with('roles', 'department', 'urusan');
    }

    public function show($id)
    {
        return $this->model->with('roles', 'department', 'urusan')->find($id);
    }
}
