<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public $model;
    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->model = $user;
    }
}