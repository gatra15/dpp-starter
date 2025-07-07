<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
   public function getAll(Request $request);
}
