<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display librarian overview dashboard with stat metrics, quick tables, fine config, and live fine calculator.
     */
    public function index(Request $request): View
    {
        $totalBooks = Book::count();
        $totalCopies = BookCopy::count();
        $availableCopies = BookCopy::where('status', 'available')->count();
        $activeReservations = Reservation::where('status', 'pending')->count();
        $activeLoans = Loan::active()->count();
        $overdueLoansCount = Loan::overdue()->count();

        // Calculate total unpaid fines
        $unpaidOverdueLoans = Loan::where('fine_paid', false)->get();
        $totalOutstandingFines = $unpaidOverdueLoans->sum(function ($loan) {
            return $loan->currentFine();
        });

        // Quick tables
        $overdueLoansList = Loan::overdue()
            ->with(['user', 'bookCopy.book'])
            ->latest('due_date')
            ->take(5)
            ->get();

        $expiringReservationsList = Reservation::where('status', 'pending')
            ->where('expires_at', '>', now())
            ->with(['user', 'bookCopy.book'])
            ->orderBy('expires_at', 'asc')
            ->take(5)
            ->get();

        // Live fine calculator computation
        $calculatedFineResult = null;
        $calcDueDate = $request->input('due_date');
        $calcReturnDate = $request->input('returned_at');

        if ($calcDueDate) {
            try {
                $due = Carbon::parse($calcDueDate);
                $ret = $calcReturnDate ? Carbon::parse($calcReturnDate) : null;
                $calculatedFineResult = calculate_fine($due, $ret);
            } catch (\Exception $e) {
                // Ignore parsing errors for live calculator
            }
        }

        $currencySymbol = config('library.currency_symbol', '₹');

        return view('librarian.dashboard', compact(
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'activeReservations',
            'activeLoans',
            'overdueLoansCount',
            'totalOutstandingFines',
            'overdueLoansList',
            'expiringReservationsList',
            'calculatedFineResult',
            'calcDueDate',
            'calcReturnDate',
            'currencySymbol'
        ));
    }
}
