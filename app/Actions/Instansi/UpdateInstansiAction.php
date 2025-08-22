<?php

namespace App\Actions\Instansi;

use App\Repositories\InstansiRepository;
use App\DTOs\InstansiDto;
use Illuminate\Http\Request;
use App\Models\Instansi;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class UpdateInstansiAction
{
    public function __construct(protected InstansiRepository $instansiRepository)
    {
    }

    public function execute(int $id, Request $request): Instansi
    {
        DB::beginTransaction();

        try {
            $instansi = $this->instansiRepository->show($id);

            if (!$instansi) {
                throw new ModelNotFoundException("Instansi dengan ID {$id} tidak ditemukan.");
            }

            $dto = InstansiDto::fromRequest($request);
            $instansiData = $dto->toArray();

            if (empty($instansiData)) {
                throw new \Exception("Tidak ada data yang diberikan untuk memperbarui instansi.");
            }

            $updated = $this->instansiRepository->update($id, $instansiData);

            if (!$updated) {
                throw new \Exception("Gagal memperbarui data instansi dengan ID {$id}.");
            }

            $updatedInstansi = $this->instansiRepository->show($id);

            DB::commit();

            return $updatedInstansi;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui instansi (kesalahan database): ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui instansi: ' . $e->getMessage(), 0, $e);
        }
    }
}