<?php

namespace App\Actions\Instansi;

use App\Repositories\InstansiRepository;

class DeleteInstansiAction
{
    public function __construct(protected InstansiRepository $instansiRepository)
    {
        $this->instansiRepository = $instansiRepository;
    }

    public function execute($id)
    {
        return $this->instansiRepository->delete($id);
    }
}
