<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display paginated books list for librarians with search and filters.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Book::with('category')
            ->withCount([
                'copies as total_copies_count',
                'copies as available_copies_count' => function ($q) {
                    $q->where('status', 'available');
                },
            ]);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->latest()->paginate(10)->withQueryString();

        return view('librarian.books.index', compact('books', 'categories'));
    }

    /**
     * Show form to create a new book item.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        return view('librarian.books.create', compact('categories'));
    }

    /**
     * Store a newly created book and auto-generate initial copies.
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request, &$book) {
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')->store('covers', 'public');
            }

            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'isbn' => $request->isbn,
                'category_id' => $request->category_id,
                'publisher' => $request->publisher,
                'published_year' => $request->published_year,
                'description' => $request->description,
                'cover_image' => $coverPath,
            ]);

            // Auto-generate requested initial copies
            $initialCount = (int) $request->initial_copies;
            for ($i = 1; $i <= $initialCount; $i++) {
                BookCopy::create([
                    'book_id' => $book->id,
                    'copy_code' => sprintf('BK%04d-C%d', $book->id, $i),
                    'status' => 'available',
                ]);
            }
        });

        return redirect()->route('librarian.books.show', $book->id)
            ->with('success', "Book '{$book->title}' and {$request->initial_copies} copy(ies) created successfully!");
    }

    /**
     * Display detailed book information and physical copies management table.
     */
    public function show(Book $book): View
    {
        $book->load(['category', 'copies' => function ($q) {
            $q->orderBy('copy_code');
        }]);

        return view('librarian.books.show', compact('book'));
    }

    /**
     * Show form to edit existing book.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();
        return view('librarian.books.edit', compact('book', 'categories'));
    }

    /**
     * Update book record.
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $book->cover_image = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'category_id' => $request->category_id,
            'publisher' => $request->publisher,
            'published_year' => $request->published_year,
            'description' => $request->description,
        ]);

        return redirect()->route('librarian.books.show', $book->id)
            ->with('success', 'Book metadata updated successfully.');
    }

    /**
     * Add a new physical copy to a book.
     */
    public function addCopy(Book $book): RedirectResponse
    {
        $nextCopyNumber = $book->copies()->count() + 1;
        $copyCode = sprintf('BK%04d-C%d', $book->id, $nextCopyNumber);

        // Ensure unique copy code if numbers were deleted earlier
        while (BookCopy::where('copy_code', $copyCode)->exists()) {
            $nextCopyNumber++;
            $copyCode = sprintf('BK%04d-C%d', $book->id, $nextCopyNumber);
        }

        BookCopy::create([
            'book_id' => $book->id,
            'copy_code' => $copyCode,
            'status' => 'available',
        ]);

        return back()->with('success', "New copy '{$copyCode}' added to inventory.");
    }

    /**
     * Remove a physical copy if not borrowed or reserved.
     */
    public function deleteCopy(BookCopy $copy): RedirectResponse
    {
        if ($copy->status !== 'available') {
            return back()->with('error', "Copy '{$copy->copy_code}' cannot be deleted because its status is currently '{$copy->status}'.");
        }

        $copyCode = $copy->copy_code;
        $copy->delete();

        return back()->with('success', "Copy '{$copyCode}' removed from inventory.");
    }

    /**
     * Delete a book if no copies are active (borrowed/reserved).
     */
    public function destroy(Book $book): RedirectResponse
    {
        $activeCopiesCount = $book->copies()->whereIn('status', ['reserved', 'borrowed'])->count();

        if ($activeCopiesCount > 0) {
            return back()->with('error', "Cannot delete book '{$book->title}' because {$activeCopiesCount} copy(ies) are currently reserved or borrowed.");
        }

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $title = $book->title;
        $book->delete();

        return redirect()->route('librarian.books.index')
            ->with('success', "Book '{$title}' and its inventory records deleted successfully.");
    }
}
