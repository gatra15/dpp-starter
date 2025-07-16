<?php

namespace App\Repositories;

use App\Contracts\LogRepositoryInterface;
use App\Models\AuditTrail;

class LogRepository extends BaseRepository implements LogRepositoryInterface
{
    public function __construct(AuditTrail $audit_trail)
    {
        parent::__construct($audit_trail);
    }
}
