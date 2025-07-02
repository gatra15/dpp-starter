<?php

namespace App\Repositories;

use App\Contracts\LogRepositoryInterface;
use App\Models\AuditTrail;

class LogRepository extends BaseRepository implements LogRepositoryInterface
{
    public $model;
    public function __construct(AuditTrail $audit_trail)
    {
        parent::__construct($this->model);
        $this->model = $audit_trail;
    }
}