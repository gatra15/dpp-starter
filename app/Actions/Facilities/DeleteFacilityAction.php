<?php

namespace App\Actions\Facilities;

use App\Repositories\FacilityRepository;

class DeleteFacilityAction
{
    public function __construct(protected FacilityRepository $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }

    public function execute($id)
    {
        return $this->facilityRepository->delete($id);
    }
}
