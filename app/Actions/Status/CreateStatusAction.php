<?php

namespace App\Actions\Status;

use App\DTOs\StatusDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\StatusRepository;

class CreateStatusAction
{
    public function __construct(protected StatusRepository $statusRepository)
    {
        $this->statusRepository = $statusRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();

        try {
            $data = StatusDto::fromRequest($request);
            $data = $data->toArray();
            $model = $this->statusRepository->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat status: ' . $e->getMessage(), 0, $e);
        }
    }
}
