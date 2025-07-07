<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface UrusanRepositoryInterface
{
   public function getAll(Request $request);
}