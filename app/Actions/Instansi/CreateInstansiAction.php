<?php

namespace App\Actions\Instansi;

use App\DTOs\InstansiDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\InstansiRepository;

class CreateInstansiAction
{
    public function __construct(protected InstansiRepository $instansiRepository)
    {
        $this->instansiRepository = $instansiRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();

        try {
            $data = InstansiDto::fromRequest($request);
            $data = $data->toArray();
            $model = $this->instansiRepository->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat instansi: ' . $e->getMessage(), 0, $e);
        }
    }
}
