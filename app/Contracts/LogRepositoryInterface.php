<?php

namespace App\Contracts;

interface LogRepositoryInterface
{
   public function getAll();
   public function show($id);
}