<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request; // Import Request
use Illuminate\Database\Eloquent\Builder; // Import Builder
use Illuminate\Pagination\LengthAwarePaginator; // Import LengthAwarePaginator
use Illuminate\Database\Eloquent\Collection; // Import Collection jika no_pagination

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public $model;
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function getAll(Request $request): Builder
    {
        return $this->model->query();
    }
}
