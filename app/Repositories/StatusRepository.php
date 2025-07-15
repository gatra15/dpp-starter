<?php

namespace App\Repositories;

use App\Models\Status;
use App\Repositories\BaseRepository;
use App\Contracts\StatusRepositoryInterface;

class StatusRepository extends BaseRepository implements StatusRepositoryInterface
{
    public function __construct(Status $status)
    {
        parent::__construct($status);
    }

    public function getAll()
    {
        return $this->model->query();
    }

    public function customQuery($query)
    {
        return $this->model->where('name', $query)->first();
    }
}
