<?php

namespace App\Actions\Urusan;

use App\DTOs\UrusanDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\UrusanRepository;

class CreateUrusanAction
{
    public function __construct(protected UrusanRepository $urusanRepository)
    {
        $this->urusanRepository = $urusanRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();

        try {
            $data = UrusanDto::fromRequest($request);
            $data = $data->toArray();
            $model = $this->urusanRepository->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat urusan: ' . $e->getMessage(), 0, $e);
        }
    }
}
