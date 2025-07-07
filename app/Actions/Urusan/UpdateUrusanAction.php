<?php

namespace App\Actions\Urusan;

use App\Repositories\UrusanRepository;

class UpdateUrusanAction
{
    public function __construct(protected UrusanRepository $urusanRepository)
    {
        $this->urusanRepository = $urusanRepository;
    }

    public function execute($id, $data)
    {
        return $this->urusanRepository->update($id, $data);
    }
}
