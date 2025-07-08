<?php

namespace App\Actions\Status;

use App\Repositories\StatusRepository;

class GetOptionAction
{
    public function __construct(
        protected StatusRepository $statusRepository,
    ) {
        //
    }

    public function execute()
    {
        return $this->statusRepository->getAll()->select('id', 'name')->get();
    }
}
