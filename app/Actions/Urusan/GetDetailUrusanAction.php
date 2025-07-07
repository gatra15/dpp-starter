<?php

namespace App\Actions\Urusan;

use App\Repositories\UrusanRepository;

class GetDetailUrusanAction
{
    public function __construct(protected UrusanRepository $urusanRepository)
    {
        $this->urusanRepository = $urusanRepository;
    }
    public function execute($id)
    {
        return $this->urusanRepository->show($id);
    }
}