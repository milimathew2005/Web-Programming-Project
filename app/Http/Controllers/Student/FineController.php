<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FineController extends Controller
{
    /**
     * Display student's current active loans, returned loan history, and calculated overdue fines.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $activeLoans = Loan::where('user_id', $user->id)
            ->active()
            ->with(['bookCopy.book.category'])
            ->latest('issued_at')
            ->get();

        $pastLoans = Loan::where('user_id', $user->id)
            ->whereNotNull('returned_at')
            ->with(['bookCopy.book'])
            ->latest('returned_at')
            ->paginate(10);

        // Sum current calculated fines for all unpaid loans
        $allUnpaidLoans = Loan::where('user_id', $user->id)
            ->where('fine_paid', false)
            ->get();

        $totalOutstandingFines = $allUnpaidLoans->sum(function ($loan) {
            return $loan->currentFine();
        });

        $currencySymbol = config('library.currency_symbol', '₹');

        return view('student.fines.index', compact('activeLoans', 'pastLoans', 'totalOutstandingFines', 'currencySymbol'));
    }
}
