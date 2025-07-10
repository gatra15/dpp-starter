<?php

namespace App\Contracts;

interface BookingRepositoryInterface
{
   public function getAll();
   public function show($id);
}
