<?php

namespace App\Actions\Status;

use App\Repositories\StatusRepository;
use App\DTOs\StatusDto;
use Illuminate\Http\Request;
use App\Models\Status;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UpdateStatusAction
{
    public function __construct(protected StatusRepository $statusRepository)
    {
    }

    public function execute($id, $request)
    {
        DB::beginTransaction();

        try {
            $status = $this->statusRepository->show($id);

            if (!$status) {
                throw new ModelNotFoundException("Status with ID {$id} not found.");
            }

            $dto = StatusDto::fromRequest($request);
            $statusData = $dto->toArray();

            if (empty($statusData)) {
                throw new \Exception("Tidak ada data yang diberikan untuk memperbarui status.");
            }

            $updated = $this->statusRepository->update($id, $statusData);

            if (!$updated) {
                throw new \Exception("Gagal memperbarui data status dengan ID {$id}.");
            }

            $updatedStatus = $this->statusRepository->show($id);

            DB::commit();

            return $updatedStatus;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui status: ' . $e->getMessage(), 0, $e);
        }
    }
}