<?php

namespace App\Actions\Facilities;

use App\Repositories\FacilityRepository;

class GetOptionAction
{
    public function __construct(
        protected FacilityRepository $facilityRepository,
    ) {
        //
    }

    public function execute()
    {
        return $this->facilityRepository->getAll()->select('id', 'name')->get();
    }
}
