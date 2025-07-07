<?php

namespace App\DTOs;

class UrusanDto
{
    public string $name;
    public ?int $department_id = null;
    public ?int $head_id = null;

    public static function fromRequest($request)
    {
        $dto = new self();
        $dto->name = $request->input('name');
        $dto->department_id = !empty($request->input('department_id')) ? $request->input('department_id') : null;
        $dto->head_id = !empty($request->input('head_id')) ? $request->input('head_id') : null;

        return $dto;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'department_id' => $this->department_id ?? null,
            'head_id' => $this->head_id ?? null,
        ];
    }
}
