<?php

namespace App\Actions\Users;

use App\Repositories\UserRepository;
use App\DTOs\UserDto;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($request)
    {
        $dto = UserDTO::fromRequest($request);
        $data = $dto->toArray();
        $data['password'] = Hash::make($data['password']);
        $model = $this->userRepository->create($data);
        if ($model) {
            return $model;
        } else {
            throw new \Exception('Failed to create user');
        }
    }
}
