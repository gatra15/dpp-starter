<?php

namespace App\Actions\Status;

use App\DTOs\StatusDto;
use App\Repositories\StatusRepository;

class CreateStatusAction
{
    public function __construct(protected StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function execute($request)
    {
        $data = StatusDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->statusRepository->create($data);
        if ($model) {
            return $model;
        } else {
            throw new \Exception('Failed to create status');
        }
    }
}
