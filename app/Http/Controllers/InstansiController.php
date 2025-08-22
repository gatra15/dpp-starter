<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\InstansiService;
use Illuminate\Validation\ValidationException;

class InstansiController extends Controller
{
    protected InstansiService $instansiService;

    public function __construct(InstansiService $instansiService)
    {
        $this->instansiService = $instansiService;
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try {
            $response = $this->instansiService->getAll($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar instansi: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'required|string|max:255|unique:instansi,name',
            ]);

            $response = $this->instansiService->create($request);
            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal membuat instansi: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->instansiService->getDetail($id);
            return response()->json($response);
        } catch (\ErrorException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil detail instansi: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name'        => 'sometimes|string|max:255|unique:instansi,name,' . $id,
            ]);

            $response = $this->instansiService->update($id, $request);
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal memperbarui instansi: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->instansiService->delete($id);
            return response()->json($response);
        } catch (\ErrorException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus instansi: ' . $e->getMessage()], 500);
        }
    }

    public function options()
    {
        try {
            // Asumsi InstansiService memiliki method getOptions
            $options = $this->instansiService->getOptions();
            return response()->json([
                'status' => true,
                'message' => 'Daftar opsi instansi berhasil diambil',
                'data' => $options
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar opsi instansi: ' . $e->getMessage()], 500);
        }
    }
}
