<?php

namespace App\DTOs;

use Carbon\Carbon;

class LogDto
{
    public string $entity_type;
    public int $entity_id;
    public string $action;
    public int $performed_by;
    public  $datetime;

    public static function fromModel($data)
    {
        $dto = new self();
        $dto->entity_type   = $data['model'];
        $dto->entity_id     = $data['model_id'];
        $dto->action        = $data['action'];
        $dto->performed_by  = $data['user_id'];
        $dto->datetime      = Carbon::now()->format('Y-m-d H:i:s');

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'entity_type' => $this->entity_type,
            'entity_id'   => $this->entity_id,
            'action'      => $this->action,
            'performed_by'=> $this->performed_by,
            'datetime'    => $this->datetime,
        ];
    }
}