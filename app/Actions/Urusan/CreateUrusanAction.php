<?php

namespace App\Actions\Urusan;

use App\DTOs\UrusanDto;
use App\Repositories\UrusanRepository;

class CreateUrusanAction
{
    public function __construct(protected UrusanRepository $urusanRepository)
    {
        $this->urusanRepository = $urusanRepository;
    }

    public function execute($request)
    {
        $data = UrusanDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->urusanRepository->create($data);
        if ($model) {
            return $model;
        } else {
            throw new \Exception('Failed to create department');
        }
    }
}