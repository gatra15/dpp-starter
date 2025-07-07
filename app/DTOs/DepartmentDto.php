<?php

namespace App\DTOs;

use Illuminate\Http\Client\Request;

class DepartmentDto
{
    public string $name;
    public ?int $head_id = null;

    public static function fromRequest(Request $request)
    {
        $dto = new self();
        $dto->name = $request->input('name');
        $dto->head_id = $request->input('head_id');

        return $dto;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'head_id' => $this->head_id,
        ];
    }
}