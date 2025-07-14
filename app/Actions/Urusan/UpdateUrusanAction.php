<?php

namespace App\Actions\Urusan;

use App\Repositories\UrusanRepository;
use App\DTOs\UrusanDto;
use Illuminate\Http\Request;
use App\Models\Urusan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class UpdateUrusanAction
{
    public function __construct(protected UrusanRepository $urusanRepository)
    {
    }

    public function execute(int $id, Request $request): Urusan
    {
        DB::beginTransaction();

        try {
            $urusan = $this->urusanRepository->show($id);

            if (!$urusan) {
                throw new ModelNotFoundException("Urusan dengan ID {$id} tidak ditemukan.");
            }

            $dto = UrusanDto::fromRequest($request);
            $urusanData = $dto->toArray();

            if (empty($urusanData)) {
                throw new \Exception("Tidak ada data yang diberikan untuk memperbarui urusan.");
            }

            $updated = $this->urusanRepository->update($id, $urusanData);

            if (!$updated) {
                throw new \Exception("Gagal memperbarui data urusan dengan ID {$id}.");
            }

            $updatedUrusan = $this->urusanRepository->show($id);

            DB::commit();

            return $updatedUrusan;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui urusan (kesalahan database): ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui urusan: ' . $e->getMessage(), 0, $e);
        }
    }
}