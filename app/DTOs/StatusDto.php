<?php

namespace App\DTOs;

class StatusDto
{
    public string $name;

    public static function fromRequest($request)
    {
        $dto = new self();
        $dto->name = $request->input('name');

        return $dto;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
        ];
    }
}
