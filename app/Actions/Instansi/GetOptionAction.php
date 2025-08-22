<?php

namespace App\Actions\Instansi;

use App\Repositories\InstansiRepository;

class GetOptionAction
{
    public function __construct(
        protected InstansiRepository $instansiRepository,
    ) {
        //
    }

    public function execute()
    {
        return $this->instansiRepository->getAll()->select('id', 'name')->get();
    }
}
