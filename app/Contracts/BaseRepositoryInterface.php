<?php

namespace App\Contracts;

interface BaseRepositoryInterface
{
   public function show($id);
   public function create($data);
   public function update($id, $data);
   public function delete($id);
}
