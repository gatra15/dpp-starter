<?php

namespace App\Contracts;

use App\Models\Department;
use Illuminate\Http\Request;

interface DepartmentRepositoryInterface
{
   public function getAll(Request $request);
}
