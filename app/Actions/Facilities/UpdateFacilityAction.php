<?php

namespace App\Actions\Facilities;

use App\Repositories\FacilityRepository;

class UpdateFacilityAction
{
    public function __construct(protected FacilityRepository $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }

    public function execute($id, $request)
    {
        $data = $request->all();
        $this->facilityRepository->update($id, $data);
        return $this->facilityRepository->show($id);
    }
}
