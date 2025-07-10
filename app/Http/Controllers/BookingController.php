<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\BookingService;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        try {
            $response = $this->bookingService->getAll($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil daftar booking: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'room_id'       => 'required|integer|exists:rooms,id',
                'title'         => 'required|string|max:255',
                'start_time'    => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
                'end_time'      => 'required|date_format:Y-m-d H:i:s|after:start_time',
                'participants'  => 'nullable|integer|min:1',
                'information'   => 'nullable|string',
            ]);

            $response = $this->bookingService->create($request);
            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal membuat booking: ' . $e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $response = $this->bookingService->getDetail($id);
            return response()->json($response);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil detail booking: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $request->validate([
                'room_id'       => 'sometimes|integer|exists:rooms,id',
                'title'         => 'sometimes|string|max:255',
                'start_time'    => 'sometimes|date_format:Y-m-d H:i:s|after_or_equal:now',
                'end_time'      => 'sometimes|date_format:Y-m-d H:i:s|after:start_time',
                'participants'  => 'sometimes|nullable|integer|min:1',
                'information'   => 'sometimes|nullable|string',
                'status_id'     => 'sometimes|integer|exists:statuses,id',
            ]);

            if ($request->has('start_time') && $request->has('end_time')) {
                $start = Carbon::parse($request->input('start_time'));
                $end = Carbon::parse($request->input('end_time'));
                if ($end->lessThanOrEqualTo($start)) {
                    throw ValidationException::withMessages([
                        'end_time' => ['Waktu selesai harus setelah waktu mulai.']
                    ]);
                }
            }

            $response = $this->bookingService->update($id, $request);
            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal memperbarui booking: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $response = $this->bookingService->delete($id);
            return response()->json($response);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus booking: ' . $e->getMessage()], 500);
        }
    }

    public function approve(int $bookingId)
    {
        try {
            $response = $this->bookingService->approve($bookingId);
            return response()->json(['status' => true, 'message' => 'Booking berhasil disetujui', 'data' => $response]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menyetujui booking: ' . $e->getMessage()], 500);
        }
    }

    public function reject(int $bookingId)
    {
        try {
            $response = $this->bookingService->reject($bookingId);
            return response()->json(['status' => true, 'message' => 'Booking berhasil ditolak', 'data' => $response]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menolak booking: ' . $e->getMessage()], 500);
        }
    }
}
