<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\UrusanService;
use Illuminate\Validation\ValidationException;

class UrusanController extends Controller
{
    protected UrusanService $urusanService;

    public function __construct(UrusanService $urusanService)
    {
        $this->urusanService = $urusanService;
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try {
            $response = $this->urusanService->getAll($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar urusan: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'required|string|max:255|unique:urusan,name',
                'department_id' => 'nullable|integer|exists:departments,id',
                'head_id'       => 'nullable|integer|exists:users,id',
            ]);

            $response = $this->urusanService->create($request);
            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal membuat urusan: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->urusanService->getDetail($id);
            return response()->json($response);
        } catch (\ErrorException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil detail urusan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name'        => 'sometimes|string|max:255|unique:urusan,name,' . $id,
                'department_id' => 'nullable|integer|exists:departments,id',
                'head_id'       => 'nullable|integer|exists:users,id',
            ]);

            $response = $this->urusanService->update($id, $request);
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal memperbarui urusan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->urusanService->delete($id);
            return response()->json($response);
        } catch (\ErrorException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus urusan: ' . $e->getMessage()], 500);
        }
    }
}
