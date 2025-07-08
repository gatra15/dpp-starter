<?php

namespace App\Repositories;

use App\Models\Facility;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use App\Contracts\FacilityRepositoryInterface;

class FacilityRepository extends BaseRepository implements FacilityRepositoryInterface
{
    public function __construct(Facility $facility)
    {
        parent::__construct($facility);
    }

    public function getAll(Request $request)
    {
        return $this->model->query();
    }
}
