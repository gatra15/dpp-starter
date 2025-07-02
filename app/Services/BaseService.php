<?php

namespace App\Services;

class BaseService
{
    public function __construct(protected $module)
    {
        $this->module = $module;
    }

    public function log($action, $user, $datetime, $id) {}
}
