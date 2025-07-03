<?php

namespace App\Actions\Users;
use App\Repositories\UserRepository;

class UpdateUserAction
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id, $data)
    {
        return $this->userRepository->update($id, $data);
    }

}