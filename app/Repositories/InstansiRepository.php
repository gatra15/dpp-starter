<?php

namespace App\Repositories;

use App\Models\Instansi;
use Illuminate\Http\Request;
use App\Contracts\InstansiRepositoryInterface;

class InstansiRepository extends BaseRepository implements InstansiRepositoryInterface
{
    public function __construct(Instansi $instansi)
    {
        parent::__construct($instansi);
    }

    public function getAll()
    {
        return $this->model->query();
    }
}
