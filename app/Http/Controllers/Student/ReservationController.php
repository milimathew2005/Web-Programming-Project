<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display active and past book reservations for the logged-in student.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $activeReservations = Reservation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['bookCopy.book.category'])
            ->latest('reserved_at')
            ->get();

        $pastReservations = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['collected', 'cancelled', 'expired'])
            ->with(['bookCopy.book'])
            ->latest('updated_at')
            ->paginate(10);

        return view('student.reservations.index', compact('activeReservations', 'pastReservations'));
    }

    /**
     * Cancel an active reservation and free the reserved copy back to available status.
     */
    public function cancel(Request $request, Reservation $reservation): RedirectResponse
    {
        if ($reservation->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be cancelled.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'cancelled']);

            if ($reservation->bookCopy && $reservation->bookCopy->status === 'reserved') {
                $reservation->bookCopy->update(['status' => 'available']);
            }
        });

        return back()->with('success', 'Reservation cancelled successfully. The book copy has been freed for others.');
    }
}
