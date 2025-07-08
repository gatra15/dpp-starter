<?php

namespace App\Actions\Status;

use App\Repositories\StatusRepository;

class UpdateStatusAction
{
    public function __construct(protected StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function execute($id, $request)
    {
        $data = $request->all();
        $this->statusRepository->update($id, $data);
        return $this->statusRepository->show($id);
    }
}
