<?php

namespace App\Actions\Users;

use App\Repositories\UserRepository;

class GetDetailUserAction
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($id)
    {
        return $this->userRepository->show($id);
    }
}
