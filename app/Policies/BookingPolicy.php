<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;
use App\Models\Status;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function approve(User $user, Booking $booking): Response
    {
        $user->loadMissing('department');
        $booking->loadMissing('user.department');

        $pendingStatusId = Status::where('name', 'pending')->first()?->id;
        $pimpinanApprovedStatusId = Status::where('name', 'pimpinan_approved')->first()?->id;
        $approvedStatusId = Status::where('name', 'approved')->first()?->id;
        $rejectedStatusId = Status::where('name', 'rejected')->first()?->id;

        if (!$pendingStatusId || !$pimpinanApprovedStatusId || !$approvedStatusId || !$rejectedStatusId) {
            return Response::deny('Status default tidak ditemukan. Hubungi administrator.');
        }

        if ($booking->status_id === $approvedStatusId) {
            return Response::deny('Booking ini sudah disetujui.');
        }
        if ($booking->status_id === $rejectedStatusId) {
            return Response::deny('Booking ini sudah ditolak.');
        }

        $isPureHR = $user->hasRole('HR') && !$user->hasRole('pimpinan');
        $isPurePimpinan = $user->hasRole('pimpinan') && !$user->hasRole('HR');
        $isCombinedHRandPimpinan = $user->hasRole('HR') && $user->hasRole('pimpinan');

        if ($isCombinedHRandPimpinan) {
            if (!$user->department_id || !$booking->user || !$booking->user->department_id) {
                return Response::deny('Kombinasi HR+Pimpinan membutuhkan informasi departemen yang lengkap untuk otorisasi.');
            }

            if ($user->department_id === $booking->user->department_id) {
                if (in_array($booking->status_id, [$pendingStatusId, $pimpinanApprovedStatusId])) {
                    return Response::allow();
                }
                return Response::deny('Kombinasi HR+Pimpinan (departemen sama) hanya bisa menyetujui booking yang pending atau pimpinan-approved.');
            } else {
                if ($booking->status_id === $pimpinanApprovedStatusId) {
                    return Response::allow();
                }
                return Response::deny('Kombinasi HR+Pimpinan (departemen berbeda) hanya bisa menyetujui booking yang pimpinan-approved.');
            }
        }

        if ($isPurePimpinan) {
            if ($booking->status_id !== $pendingStatusId) {
                return Response::deny('Pimpinan (murni) hanya bisa menyetujui booking yang berstatus "pending".');
            }

            if (!$user->department_id || !$booking->user || !$booking->user->department_id) {
                return Response::deny('Informasi departemen tidak lengkap untuk pengecekan pimpinan.');
            }

            if ($user->department_id === $booking->user->department_id) {
                return Response::allow();
            }
            return Response::deny('Anda (Pimpinan murni) hanya dapat menyetujui booking dari bawahan yang satu departemen dengan Anda.');
        }

        if ($isPureHR) {
            return Response::deny('HR (murni) tidak memiliki izin untuk menyetujui booking.');
        }

        return Response::deny('Anda tidak memiliki izin yang cukup untuk menyetujui booking ini.');
    }

    public function reject(User $user, Booking $booking): Response
    {
        $user->loadMissing('department');
        $booking->loadMissing('user.department');

        $rejectedStatusId = Status::where('name', 'rejected')->first()?->id;
        $approvedStatusId = Status::where('name', 'approved')->first()?->id;

        if (!$rejectedStatusId || !$approvedStatusId) {
            return Response::deny('Status default tidak ditemukan. Hubungi administrator.');
        }

        if ($booking->status_id === $rejectedStatusId) {
            return Response::deny('Booking ini sudah ditolak.');
        }
        if ($booking->status_id === $approvedStatusId) {
            return Response::deny('Booking ini sudah disetujui dan tidak dapat ditolak.');
        }

        $isPureHR = $user->hasRole('HR') && !$user->hasRole('pimpinan');
        $isPurePimpinan = $user->hasRole('pimpinan') && !$user->hasRole('HR');
        $isCombinedHRandPimpinan = $user->hasRole('HR') && $user->hasRole('pimpinan');

        if ($isCombinedHRandPimpinan || $isPureHR) { 
            return Response::allow();
        }

        if ($isPurePimpinan) {
            if (!$user->department_id) {
                return Response::deny('Akun pimpinan Anda tidak terkait dengan departemen mana pun.');
            }
            if (!$booking->user || !$booking->user->department_id) {
                return Response::deny('User pembuat booking tidak ditemukan atau tidak memiliki departemen.');
            }
            if ($user->department_id === $booking->user->department_id) {
                return Response::allow();
            }
            return Response::deny('Anda (Pimpinan murni) hanya dapat menolak booking dari bawahan yang satu departemen dengan Anda.');
        }

        return Response::deny('Anda tidak memiliki izin yang cukup untuk menolak booking ini.');
    }
}