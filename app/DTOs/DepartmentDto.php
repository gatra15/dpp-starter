<?php

namespace App\DTOs;

class DepartmentDto
{
    public string $name;
    public ?int $head_id = null;

    public static function fromRequest($request)
    {
        $dto = new self();
        $dto->name = $request->input('name');
        $dto->head_id = !empty($request->input('head_id')) ? $request->input('head_id') : null;

        return $dto;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'head_id' => $this->head_id ?? null,
        ];
    }
}
