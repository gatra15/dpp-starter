<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Status;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\BookingService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Actions\Bookings\RejectBookingAction;
use App\Actions\Bookings\ApproveBookingAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    protected BookingService $bookingService;
    protected ApproveBookingAction $approveBookingAction;
    protected RejectBookingAction $rejectBookingAction;

    public function __construct(BookingService $bookingService, ApproveBookingAction $approveBookingAction, RejectBookingAction $rejectBookingAction)
    {
        $this->bookingService = $bookingService;
        $this->approveBookingAction = $approveBookingAction;
        $this->rejectBookingAction = $rejectBookingAction;
        $this->middleware('auth:api')->except(['publicSchedule', 'todaySchedule']);
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

    public function approve(int $id)
    {
        try {
            $booking = Booking::with('user.department')->findOrFail($id);

            $this->authorize('approve', $booking);

            $response = $this->bookingService->approve($id);
            return response()->json(['status' => true, 'message' => 'Booking berhasil disetujui', 'data' => $response]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Booking tidak ditemukan.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menyetujui booking: ' . $e->getMessage()], 500);
        }
    }

    public function reject(int $id)
    {
        try {
            $booking = Booking::with('user.department')->findOrFail($id);

            $this->authorize('reject', $booking);

            $response = $this->bookingService->reject($id);
            return response()->json(['status' => true, 'message' => 'Booking berhasil ditolak', 'data' => $response]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => 'Booking tidak ditemukan.'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menolak booking: ' . $e->getMessage()], 500);
        }
    }

    public function publicSchedule(Request $request)
    {
        try {
            $approvedStatus = Status::where('name', 'approved')->first();
            if (!$approvedStatus) {
                return response()->json(['status' => false, 'message' => 'Status "approved" tidak ditemukan. Konfigurasi awal mungkin belum lengkap.'], 500);
            }

            $request->merge([
                'status_id' => $approvedStatus->id,
            ]);

            if (!$request->has('start_time_after') && !$request->has('no_date_filter')) {
                $today = Carbon::now()->toDateString();
                $request->merge(['start_time_after' => $today . ' 00:00:00']);
            }

            $response = $this->bookingService->getAll($request);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil jadwal booking publik: ' . $e->getMessage()], 500);
        }
    }

    public function todaySchedule(Request $request)
    {
        try {
            $approvedStatus = Status::where('name', 'approved')->first();
            if (!$approvedStatus) {
                return response()->json(['status' => false, 'message' => 'Status "approved" tidak ditemukan. Konfigurasi awal mungkin belum lengkap.'], 500);
            }

            // Dapatkan tanggal hari ini, atau dari request jika ada
            $dateString = $request->input('date', Carbon::now()->toDateString());
            $date = Carbon::parse($dateString);

            // Tentukan rentang waktu untuk hari tersebut
            $startOfDay = $date->startOfDay()->toDateTimeString();
            $endOfDay = $date->endOfDay()->toDateTimeString();

            $request->merge([
                'status_id' => $approvedStatus->id,
                'start_time_after' => $startOfDay,
                'start_time_before' => $endOfDay,
            ]);

            $response = $this->bookingService->getAll($request);

            return response()->json($response);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal mengambil jadwal booking publik: ' . $e->getMessage()], 500);
        }
    }
}
