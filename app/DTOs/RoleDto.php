<?php

namespace App\DTOs;

use Illuminate\Http\Request;

class RoleDto
{
    public string $name;

    public static function fromRequest(Request $request)
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
