<?php

namespace App\Actions;

use App\DTOs\LogDto;
use App\Contracts\LogRepositoryInterface;

class LogAction
{
    public function exec(array $data)
    {
        $dto = LogDto::fromModel($data);
        $model = app(LogRepositoryInterface::class)->create($dto->toArray());

        return $model;
    }
}
