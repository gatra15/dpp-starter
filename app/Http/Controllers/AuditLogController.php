<?php

namespace App\Http\Controllers; // Namespace ini sudah sesuai dengan routes/api.php

use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Pastikan ini di-import
use App\Repositories\LogRepository; // Import LogRepository Anda
use Illuminate\Database\Eloquent\ModelNotFoundException; // Untuk penanganan 404
use Illuminate\Auth\Access\AuthorizationException; // Untuk 403 jika Policy digunakan

class AuditLogController extends Controller
{
    // Menggunakan Constructor Property Promotion untuk inisialisasi repository
    public function __construct(protected LogRepository $logRepository) {}

    public function index(Request $request)
    {
        try {
            // Menggunakan repository untuk mendapatkan query dasar
            $query = $this->logRepository->getAll();

            // Filter (sesuai dengan yang ada di frontend)
            if ($request->filled('entity_type')) {
                $query->where('entity_type', 'like', '%' . $request->input('entity_type') . '%');
            }
            if ($request->filled('action')) {
                $query->where('action', $request->input('action'));
            }
            if ($request->filled('performed_by_id')) {
                $query->where('performed_by', $request->input('performed_by_id'));
            }
            if ($request->filled('start_date')) {
                $query->where('datetime', '>=', $request->input('start_date') . ' 00:00:00');
            }
            if ($request->filled('end_date')) {
                $query->where('datetime', '<=', $request->input('end_date') . ' 23:59:59');
            }

            // Paginate hasilnya
            $logs = $query->latest('datetime')->paginate($request->input('per_page', 10));

            // --- PENTING: KEMBALIKAN DALAM FORMAT STANDAR ---
            return response()->json([
                'status' => true,
                'message' => 'Log audit berhasil diambil',
                'data' => $logs // $logs di sini sudah objek paginasi
            ]);
        } catch (AuthorizationException $e) { // Jika ada middleware can/policy di method ini
            return response()->json(['status' => false, 'message' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            // Log error untuk debugging lebih lanjut
            throw new \Exception('Gagal mengambil log audit: ' . $e->getMessage(), 500);
        }
    }

    public function show(int $id) // Tambahkan type hint int
    {
        try {
            $log = $this->logRepository->show($id); // Gunakan repository show()

            if (!$log) {
                throw new ModelNotFoundException("Log audit dengan ID {$id} tidak ditemukan.");
            }

            return response()->json([
                'status' => true,
                'message' => 'Detail log audit berhasil diambil',
                'data' => $log
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            throw new \Exception('Gagal mengambil detail log audit: ' . $e->getMessage(), 500);
        }
    }
}
