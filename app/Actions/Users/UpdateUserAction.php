<?php

namespace App\Actions\Users;

use App\Repositories\UserRepository;
use App\DTOs\UserDto;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class UpdateUserAction
{
    public function __construct(protected UserRepository $userRepository) {}

    public function execute(int $id, Request $request): User
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->show($id);

            if (!$user) {
                throw new ModelNotFoundException("User dengan ID {$id} tidak ditemukan.");
            }


            $dto = UserDto::fromRequest($request);
            $dataToUpdate = $dto->toArray();

            if (isset($dataToUpdate['password']) && !empty($dataToUpdate['password'])) {
                $dataToUpdate['password'] = Hash::make($dataToUpdate['password']);
            } else {
                unset($dataToUpdate['password']);
            }

            if (!empty($dataToUpdate)) {
                $updated = $this->userRepository->update($id, $dataToUpdate);
                if (!$updated) {
                    throw new \Exception("Gagal memperbarui data dasar user dengan ID {$id}.");
                }
            }

            if ($request->has('roles')) {
                $roles = $request->input('roles');
                $user->syncRoles($roles);
            }

            $updatedUser = $this->userRepository->show($id);

            DB::commit();

            return $updatedUser;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui user (kesalahan database): ' . $e->getMessage(), 0, $e);
        } catch (RoleDoesNotExist $e) {
            DB::rollBack();
            throw new \Exception("Gagal memperbarui peran: " . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui user: ' . $e->getMessage(), 0, $e);
        }
    }
}
