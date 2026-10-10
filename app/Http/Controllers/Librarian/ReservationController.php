<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display all student reservations with status filters and search.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');

        $query = Reservation::with(['user', 'bookCopy.book']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->whereHas('user', function ($uq) use ($term) {
                    $uq->where('name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%")
                      ->orWhere('student_id', 'like', "%{$term}%");
                })->orWhereHas('bookCopy', function ($cq) use ($term) {
                    $cq->where('copy_code', 'like', "%{$term}%")
                      ->orWhereHas('book', function ($bq) use ($term) {
                          $bq->where('title', 'like', "%{$term}%");
                      });
                });
            });
        }

        $reservations = $query->latest('reserved_at')->paginate(15)->withQueryString();

        return view('librarian.reservations.index', compact('reservations', 'status'));
    }

    /**
     * Issue book copy from a pending reservation (converts reservation to active loan).
     */
    public function issue(Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be issued.');
        }

        try {
            DB::transaction(function () use ($reservation, &$loan) {
                // Update reservation status to collected
                $reservation->update(['status' => 'collected']);

                // Update copy status to borrowed
                $reservation->bookCopy->update(['status' => 'borrowed']);

                // Create loan record
                $loanDays = config('library.loan_days', 14);
                $loan = Loan::create([
                    'user_id' => $reservation->user_id,
                    'book_copy_id' => $reservation->book_copy_id,
                    'reservation_id' => $reservation->id,
                    'issued_at' => now(),
                    'due_date' => now()->addDays($loanDays),
                    'returned_at' => null,
                    'fine_amount' => 0.00,
                    'fine_paid' => false,
                ]);
            });

            return back()->with('success', "Book '{$reservation->bookCopy->book->title}' issued to {$reservation->user->name}! Due date: {$loan->due_date->format('M d, Y')}.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a student reservation and release copy.
     */
    public function cancel(Reservation $reservation): RedirectResponse
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Only pending reservations can be cancelled.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'cancelled']);

            if ($reservation->bookCopy && $reservation->bookCopy->status === 'reserved') {
                $reservation->bookCopy->update(['status' => 'available']);
            }
        });

        return back()->with('success', 'Reservation cancelled and copy returned to available inventory.');
    }
}
