<?php

namespace App\Actions\Status;

use App\Repositories\StatusRepository;

class DeleteStatusAction
{
    public function __construct(protected StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function execute($id)
    {
        return $this->statusRepository->delete($id);
    }
}
