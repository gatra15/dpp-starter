<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Illuminate\Routing\Controller;
use App\Actions\Bookings\RejectBookingAction;
use App\Actions\Bookings\ApproveBookingAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected ApproveBookingAction $approveBookingAction;
    protected RejectBookingAction $rejectBookingAction;

    public function __construct(BookingService $bookingService, ApproveBookingAction $approveBookingAction, RejectBookingAction $rejectBookingAction)
    {
        $this->bookingService = $bookingService;
        $this->middleware('auth:api');
        $this->approveBookingAction = $approveBookingAction;
        $this->rejectBookingAction = $rejectBookingAction;
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

    public function approve(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id); 
        $booking->load('user.department');

        $this->authorize('approve', $booking);

        try {
            $approvedBooking = $this->approveBookingAction->execute($id);

            return response()->json([
                'status' => true,
                'message' => 'Booking berhasil disetujui.',
                'data' => $approvedBooking
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyetujui booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->load('user.department');
        $this->authorize('reject', $booking);

        try {
            $rejectedBooking = $this->rejectBookingAction->execute($id);

            return response()->json([
                'status' => true,
                'message' => 'Booking berhasil ditolak.',
                'data' => $rejectedBooking
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menolak booking: ' . $e->getMessage()
            ], 500);
        }
    }
}
