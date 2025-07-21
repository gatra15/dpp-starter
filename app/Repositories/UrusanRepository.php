<?php

namespace App\Repositories;

use App\Models\Urusan;
use Illuminate\Http\Request;
use App\Contracts\UrusanRepositoryInterface;

class UrusanRepository extends BaseRepository implements UrusanRepositoryInterface
{
    public function __construct(Urusan $urusan)
    {
        parent::__construct($urusan);
    }

    public function getAll()
    {
        return $this->model->query()->with('department','head');
    }
}
