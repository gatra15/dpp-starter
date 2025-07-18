<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try {
            $response = $this->userService->getAll($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar user: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'required|string|max:255',
                'username'      => 'required|string|unique:users,username',
                'email'         => 'required|string|email|max:255|unique:users',
                'department_id' => 'integer|exists:departments,id',
                'urusan_id'     => 'integer|exists:urusan,id',
            ]);

            $response = $this->userService->create($request);
            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal membuat user: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->userService->getDetail($id);
            return response()->json($response);
        } catch (\ErrorException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil detail user: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name'          => 'sometimes|string|max:255',
                'username'      => 'sometimes|string|unique:users,username,' . $id,
                'email'         => 'sometimes|string|email|max:255|unique:users,email,' . $id,
                'department_id' => 'integer|exists:departments,id',
                'urusan_id'     => 'integer|exists:urusan,id',
            ]);

            $response = $this->userService->update($id, $request);
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal memperbarui user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->userService->delete($id);
            return response()->json($response);
        } catch (\ErrorException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus user: ' . $e->getMessage()], 500);
        }
    }

    public function getAuthenticatedUser(Request $request)
    {
        return response()->json(['status' => true, 'data' => $request->user()->load('roles', 'department', 'urusan')]);
    }
}
