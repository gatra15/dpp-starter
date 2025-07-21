<?php

namespace App\Repositories;

use App\Contracts\LogRepositoryInterface;
use App\Models\AuditTrail;
use Illuminate\Database\Eloquent\Builder;

class LogRepository extends BaseRepository implements LogRepositoryInterface
{
    public function __construct(AuditTrail $auditTrail)
    {
        parent::__construct($auditTrail);
    }

    public function getAll(): Builder
    {
        // Untuk index, eager load performedByUser secara default
        return $this->model->query()->with('performedByUser');
    }

    public function show($id)
    {
        return $this->model->with('performedByUser')->find($id);
    }
}
