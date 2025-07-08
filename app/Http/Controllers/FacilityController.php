<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\FacilityService;
use Illuminate\Validation\ValidationException;

class FacilityController extends Controller
{
    public function __construct(protected FacilityService $facilityService)
    {
        $this->facilityService = $facilityService;
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try {
            $response = $this->facilityService->getAll($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar fasilitas: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:facilities,name',
            ]);

            $response = $this->facilityService->create($request);
            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal membuat fasilitas: ' . $e->getMessage()], 500);
        }
    }

    public function options()
    {
        try {
            $options = $this->facilityService->getOptions();
            return response()->json([
                'status' => true,
                'message' => 'Daftar opsi fasilitas berhasil diambil',
                'data' => $options
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar opsi fasilitas: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'sometimes|string|max:255|unique:facilities,name,' . $id,
            ]);

            $response = $this->facilityService->update($id, $request);
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal memperbarui fasilitas: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->facilityService->delete($id);
            return response()->json($response);
        } catch (\ErrorException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus fasilitas: ' . $e->getMessage()], 500);
        }
    }
}
