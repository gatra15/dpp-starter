<?php

namespace App\Actions\Facilities;

use App\Repositories\FacilityRepository;

class GetDetailFacilityAction
{
    public function __construct(protected FacilityRepository $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }
    public function execute($id)
    {
        return $this->facilityRepository->show($id);
    }
}
