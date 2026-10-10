<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FineController extends Controller
{
    /**
     * Display fines management page with active configuration summary and payment list.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'unpaid');

        // Fetch loans that have a non-zero fine or are currently overdue
        $query = Loan::with(['user', 'bookCopy.book']);

        if ($status === 'unpaid') {
            $query->where('fine_paid', false)
                  ->where(function ($q) {
                      $q->where('fine_amount', '>', 0)
                        ->orWhere(function ($oq) {
                            $oq->whereNull('returned_at')->where('due_date', '<', now());
                        });
                  });
        } elseif ($status === 'paid') {
            $query->where('fine_paid', true)->where('fine_amount', '>', 0);
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

        $loans = $query->latest('due_date')->paginate(15)->withQueryString();

        // Active fine configuration parameters (read-only)
        $fineConfig = [
            'loan_days' => config('library.loan_days', 14),
            'fine_per_day' => config('library.fine_per_day', 2.00),
            'grace_days' => config('library.grace_days', 0),
            'max_fine' => config('library.max_fine', 500.00),
            'max_active_items' => config('library.max_active_items_per_user', 3),
            'currency_symbol' => config('library.currency_symbol', '₹'),
        ];

        return view('librarian.fines.index', compact('loans', 'status', 'fineConfig'));
    }

    /**
     * Mark outstanding fine as paid by student.
     */
    public function markPaid(Loan $loan): RedirectResponse
    {
        // Compute and freeze final fine if unreturned or update fine_paid
        DB::transaction(function () use ($loan) {
            $currentFine = $loan->currentFine();
            $loan->update([
                'fine_amount' => $currentFine > 0 ? $currentFine : $loan->fine_amount,
                'fine_paid' => true,
            ]);
        });

        $currencySymbol = config('library.currency_symbol', '₹');
        return back()->with('success', "Fine of {$currencySymbol}" . number_format($loan->fine_amount, 2) . " for student {$loan->user->name} marked as PAID.");
    }

    /**
     * Display printable receipt view for fine payment.
     */
    public function receipt(Loan $loan): View
    {
        $loan->load(['user', 'bookCopy.book.category']);
        $currencySymbol = config('library.currency_symbol', '₹');
        $overdueDays = $loan->daysOverdue();
        $calculatedFine = $loan->currentFine();

        return view('librarian.fines.receipt', compact('loan', 'currencySymbol', 'overdueDays', 'calculatedFine'));
    }
}
