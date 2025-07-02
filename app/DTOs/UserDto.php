<?php

namespace App\DTOs;

class UserDto
{
    public string $name;
    public string $username;
    public string $email;
    public string $password;
    public int $department_id;
    public int $urusan_id;

    public static function fromRequest($request)
    {
        $dto = new self();
        $dto->name          = $request->input('name');
        $dto->username      = $request->input('username');
        $dto->email         = $request->input('email');
        $dto->password      = $request->input('password');
        $dto->department_id = $request->input('department_id');
        $dto->urusan_id     = $request->input('urusan_id');

        return $dto;
    }
}
