<?php

namespace App\DTOs;

class RoomDto
{
    public string $name;
    public ?int $capacity = null;
    public ?string $description = null;
    public ?bool $available = null;
    public array $facility_id = [];

    public static function fromRequest($request)
    {
        $dto = new self();
        $dto->name = $request->input('name');
        $dto->capacity = $request->input('capacity');
        $dto->description = $request->input('description');
        $dto->available = $request->boolean('available');
        $dto->facility_id = $request->input('facility_id', []);

        return $dto;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'capacity' => $this->capacity ?? null,
            'description' => $this->description ?? null,
            'available' => $this->available ?? true,
        ];
    }
}
