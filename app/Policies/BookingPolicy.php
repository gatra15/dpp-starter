<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function approve(User $user, Booking $booking)
    {
        if (!$user->hasRole(['pimpinan', 'HR'])) {
            return Response::deny('Anda tidak memiliki izin untuk menyetujui booking ini.');
        }

        if ($user->hasRole('pimpinan')) {
            if ($booking->user->department_id !== $user->department_id) {
                return Response::deny('Pimpinan hanya dapat menyetujui booking dari departemennya sendiri.');
            }
        }
        return Response::allow();
    }

    public function reject(User $user, Booking $booking)
    {
        if (!$user->hasRole(['pimpinan', 'HR'])) {
            return Response::deny('Anda tidak memiliki izin untuk menolak booking ini.');
        }
        if ($user->hasRole('pimpinan')) {
            if ($booking->user->department_id !== $user->department_id) {
                return Response::deny('Pimpinan hanya dapat menyetujui booking dari departemennya sendiri.');
            }
        }

        return Response::allow();
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Booking $booking)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Booking $booking)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Booking $booking)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Booking $booking)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Booking $booking)
    {
        //
    }
}
