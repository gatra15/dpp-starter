<?php

namespace App\Actions\Users;

use App\DTOs\UserDto;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Exceptions\RoleDoesNotExist;

class CreateUserAction
{
    public function __construct(protected UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute($request)
    {
        DB::beginTransaction();
        try {
            $dto = UserDto::fromRequest($request);
            $data = $dto->toArray();
            $data['password'] = Hash::make($data['password']);
            $model = $this->userRepository->create($data);
            if ($request->input('roles')) {
                $model->assignRole($request->input('roles'));
            }
            DB::commit();
            return $model;
        } catch (RoleDoesNotExist $e) {
            DB::rollBack();
            throw new \Exception("Gagal menetapkan peran: " . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat user: ' . $e->getMessage(), 0, $e);
        }
    }
}
