<?php

namespace App\Actions\Facilities;

use App\DTOs\FacilityDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\FacilityRepository;

class CreateFacilityAction
{
    public function __construct(protected FacilityRepository $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction(); 

        try {
        $data = FacilityDto::fromRequest($request);
        $data = $data->toArray();
        $model = $this->facilityRepository->create($data);
        
        DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat fasilitas: ' . $e->getMessage(), 0, $e);
        }
        
    }
}
