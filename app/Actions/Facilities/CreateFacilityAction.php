<?php

namespace App\Actions\Facilities;

use App\DTOs\FacilityDto;
use App\Repositories\FacilityRepository;

class CreateFacilityAction
{
    public function __construct(protected FacilityRepository $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }

    public function execute($request)
    {
        $data = FacilityDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->facilityRepository->create($data);
        if ($model) {
            return $model;
        } else {
            throw new \Exception('Failed to create facility');
        }
        
    }
}
