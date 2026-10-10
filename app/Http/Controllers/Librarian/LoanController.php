<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectIssueLoanRequest;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Display all circulation loans with status filters and search.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'active');

        $query = Loan::with(['user', 'bookCopy.book']);

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'overdue') {
            $query->overdue();
        } elseif ($status === 'returned') {
            $query->whereNotNull('returned_at');
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

        $loans = $query->latest('issued_at')->paginate(15)->withQueryString();
        $currencySymbol = config('library.currency_symbol', '₹');

        return view('librarian.loans.index', compact('loans', 'status', 'currencySymbol'));
    }

    /**
     * Show direct book issue form (select student + available book copy).
     */
    public function create(): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $availableCopies = BookCopy::where('status', 'available')
            ->with('book')
            ->get()
            ->sortBy('book.title');

        return view('librarian.loans.create', compact('students', 'availableCopies'));
    }

    /**
     * Direct issue a book copy to a student without prior online reservation.
     */
    public function store(DirectIssueLoanRequest $request): RedirectResponse
    {
        $student = User::findOrFail($request->user_id);

        if (! $student->canReserve()) {
            $maxLimit = config('library.max_active_items_per_user', 3);
            return back()->with('error', "Student {$student->name} has reached the maximum limit of {$maxLimit} active loans/reservations.");
        }

        try {
            DB::transaction(function () use ($request, $student, &$loan) {
                $copy = BookCopy::where('id', $request->book_copy_id)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (! $copy) {
                    throw new \Exception('Selected book copy is no longer available.');
                }

                if ($student->hasActiveReservationOrLoanForBook($copy->book_id)) {
                    throw new \Exception("Student {$student->name} already holds an active loan or reservation for this book title.");
                }

                // Mark copy as borrowed
                $copy->update(['status' => 'borrowed']);

                // Create loan record
                $loanDays = config('library.loan_days', 14);
                $loan = Loan::create([
                    'user_id' => $student->id,
                    'book_copy_id' => $copy->id,
                    'reservation_id' => null,
                    'issued_at' => now(),
                    'due_date' => now()->addDays($loanDays),
                    'returned_at' => null,
                    'fine_amount' => 0.00,
                    'fine_paid' => false,
                ]);
            });

            return redirect()->route('librarian.loans.index')
                ->with('success', "Book copy {$loan->bookCopy->copy_code} issued directly to {$student->name}! Due date: {$loan->due_date->format('M d, Y')}.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process book return, calculate final fine using FineCalculator, and restore copy availability.
     */
    public function returnBook(Loan $loan): RedirectResponse
    {
        if ($loan->returned_at !== null) {
            return back()->with('error', 'This book loan has already been marked as returned.');
        }

        $currencySymbol = config('library.currency_symbol', '₹');

        try {
            DB::transaction(function () use ($loan, &$calculatedFine) {
                $returnedAt = now();
                $calculatedFine = calculate_fine($loan->due_date, $returnedAt);

                $loan->update([
                    'returned_at' => $returnedAt,
                    'fine_amount' => $calculatedFine,
                    'fine_paid' => ($calculatedFine === 0.00),
                ]);

                // Set copy back to available
                if ($loan->bookCopy) {
                    $loan->bookCopy->update(['status' => 'available']);
                }
            });

            $fineMessage = $calculatedFine > 0
                ? " Outstanding Fine: {$currencySymbol}" . number_format($calculatedFine, 2)
                : ' No fine incurred.';

            return back()->with('success', "Book copy {$loan->bookCopy->copy_code} returned successfully.{$fineMessage}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
