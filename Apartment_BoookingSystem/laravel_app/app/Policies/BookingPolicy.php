<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Grant all abilities to admin users.
     */
    public function before(User $user, $ability)
    {
        if ($user->is_admin) return true;
    }

    public function view(User $user, Booking $booking)
    {
        return $booking->user_id === $user->id;
    }

    public function cancel(User $user, Booking $booking)
    {
        return $booking->user_id === $user->id;
    }
}
