<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\RoomService;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoomController extends Controller
{
    public function __construct(protected RoomService $roomService)
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try {
            $response = $this->roomService->getAll($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'required|string|max:255|unique:rooms,name',
                'capacity'      => 'nullable|integer|min:0',
                'description'   => 'nullable|string',
                'available'     => 'nullable|boolean',
                'facility_ids'  => 'sometimes|nullable|array',
                'facility_ids.*' => 'integer|exists:facilities,id',
            ]);

            $response = $this->roomService->create($request);
            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal membuat ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $response = $this->roomService->getDetail($id);
            return response()->json($response);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil detail ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $request->validate([
                'name'          => 'sometimes|string|max:255|unique:rooms,name,' . $id,
                'capacity'      => 'sometimes|nullable|integer|min:0',
                'description'   => 'sometimes|nullable|string',
                'available'     => 'sometimes|nullable|boolean',
                'facility_ids'  => 'sometimes|nullable|array',
                'facility_ids.*' => 'integer|exists:facilities,id',
            ]);

            $response = $this->roomService->update($id, $request);
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal memperbarui ruangan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $response = $this->roomService->delete($id);
            return response()->json($response);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus ruangan: ' . $e->getMessage()], 500);
        }
    }
}
