<?php

namespace App\Actions\Urusan;

use App\Repositories\UrusanRepository;

class UpdateUrusanAction
{
    public function __construct(protected UrusanRepository $urusanRepository)
    {
        $this->urusanRepository = $urusanRepository;
    }

    public function execute($id, $request)
    {
        $data = $request->all();
        $this->urusanRepository->update($id, $data);
        return $this->urusanRepository->show($id);
    }
}
