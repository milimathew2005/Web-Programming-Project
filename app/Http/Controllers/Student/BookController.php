<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReserveBookRequest;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display paginated book catalog with search, category filtering, and availability toggle.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Book::with(['category'])
            ->withCount([
                'copies as total_copies_count',
                'copies as available_copies_count' => function ($q) {
                    $q->where('status', 'available');
                },
            ]);

        // Search by Title, Author, ISBN, or Category name
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Available Only Toggle
        if ($request->boolean('available_only')) {
            $query->available();
        }

        $books = $query->latest()->paginate(12)->withQueryString();

        return view('student.books.index', compact('books', 'categories'));
    }

    /**
     * Display detailed information for a specific book.
     */
    public function show(Book $book): View
    {
        $book->load(['category', 'copies']);
        $user = auth()->user();

        $availableCopiesCount = $book->availableCopiesCount();
        $totalCopiesCount = $book->totalCopiesCount();
        $canReserve = $user->canReserve();
        $hasActiveHold = $user->hasActiveReservationOrLoanForBook($book->id);

        $disableReason = null;
        if ($availableCopiesCount === 0) {
            $disableReason = 'No copies currently available for reservation.';
        } elseif ($hasActiveHold) {
            $disableReason = 'You already hold an active reservation or loan for this book.';
        } elseif (! $canReserve) {
            $maxLimit = config('library.max_active_items_per_user', 3);
            $disableReason = "You have reached your maximum limit of {$maxLimit} active borrows/reservations.";
        }

        return view('student.books.show', compact('book', 'availableCopiesCount', 'totalCopiesCount', 'canReserve', 'hasActiveHold', 'disableReason'));
    }

    /**
     * Reserve an available copy of the book inside a DB transaction with row locking.
     */
    public function reserve(ReserveBookRequest $request, Book $book): RedirectResponse
    {
        $user = $request->user();

        // Check user active borrow limit
        if (! $user->canReserve()) {
            $maxLimit = config('library.max_active_items_per_user', 3);
            return back()->with('error', "You cannot reserve more books. Maximum limit of {$maxLimit} active reservations and loans reached.");
        }

        // Check duplicate active reservation or loan for the same book
        if ($user->hasActiveReservationOrLoanForBook($book->id)) {
            return back()->with('error', 'You already have an active reservation or loan for this book.');
        }

        try {
            DB::transaction(function () use ($book, $user, &$reservedCopy) {
                // Lock available copy for update to prevent race conditions
                $copy = BookCopy::where('book_id', $book->id)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (! $copy) {
                    throw new \Exception('No available copy of this book is currently free.');
                }

                // Mark copy as reserved
                $copy->update(['status' => 'reserved']);

                // Create reservation record
                $holdHours = config('library.reservation_hold_hours', 48);
                Reservation::create([
                    'user_id' => $user->id,
                    'book_copy_id' => $copy->id,
                    'status' => 'pending',
                    'reserved_at' => now(),
                    'expires_at' => now()->addHours($holdHours),
                ]);

                $reservedCopy = $copy;
            });

            return redirect()->route('student.reservations')->with('success', "Book '{$book->title}' (Copy {$reservedCopy->copy_code}) reserved successfully! Please collect within 48 hours.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
