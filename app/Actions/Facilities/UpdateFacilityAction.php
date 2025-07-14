<?php

namespace App\Actions\Facilities;

use App\Repositories\FacilityRepository;
use App\DTOs\FacilityDto;
use Illuminate\Http\Request;
use App\Models\Facility;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UpdateFacilityAction
{
    public function __construct(protected FacilityRepository $facilityRepository) {}

    public function execute($id, $request)
    {
        DB::beginTransaction();

        try {
            $facility = $this->facilityRepository->show($id);

            if (!$facility) {
                throw new ModelNotFoundException("Facility with ID {$id} not found.");
            }

            $dto = FacilityDto::fromRequest($request);
            $facilityData = $dto->toArray();

            if (empty($facilityData)) {
                throw new \Exception("Tidak ada data yang diberikan untuk memperbarui fasilitas.");
            }

            $updated = $this->facilityRepository->update($id, $facilityData);

            if (!$updated) {
                throw new \Exception("Gagal memperbarui data fasilitas dengan ID {$id}.");
            }

            $updatedFacility = $this->facilityRepository->show($id);

            DB::commit();

            return $updatedFacility;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui fasilitas: ' . $e->getMessage(), 0, $e);
        }
    }
}
