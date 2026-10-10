<?php

use App\Http\Controllers\Librarian\BookController as LibrarianBookController;
use App\Http\Controllers\Librarian\CategoryController as LibrarianCategoryController;
use App\Http\Controllers\Librarian\DashboardController as LibrarianDashboardController;
use App\Http\Controllers\Librarian\FineController as LibrarianFineController;
use App\Http\Controllers\Librarian\LoanController as LibrarianLoanController;
use App\Http\Controllers\Librarian\ReservationController as LibrarianReservationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\BookController as StudentBookController;
use App\Http\Controllers\Student\FineController as StudentFineController;
use App\Http\Controllers\Student\ReservationController as StudentReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Main dashboard route that redirects users to their respective role dashboards.
 */
Route::get('/dashboard', function (Request $request) {
    if ($request->user()->isLibrarian()) {
        return redirect()->route('librarian.dashboard');
    }
    return redirect()->route('books.index');
})->middleware(['auth'])->name('dashboard');

/**
 * Student Routes (Catalog, Detail, Reserve, Reservations, Fines)
 */
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/books', [StudentBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [StudentBookController::class, 'show'])->name('books.show');
    Route::post('/books/{book}/reserve', [StudentBookController::class, 'reserve'])->name('books.reserve');
    Route::get('/student/reservations', [StudentReservationController::class, 'index'])->name('student.reservations');
    Route::post('/student/reservations/{reservation}/cancel', [StudentReservationController::class, 'cancel'])->name('student.reservations.cancel');
    Route::get('/student/fines', [StudentFineController::class, 'index'])->name('student.fines');
});

/**
 * Librarian Routes (Dashboard, Books CRUD, Copies, Categories, Reservations, Loans, Fines & Receipts)
 */
Route::middleware(['auth', 'role:librarian'])->prefix('librarian')->name('librarian.')->group(function () {
    Route::get('/dashboard', [LibrarianDashboardController::class, 'index'])->name('dashboard');

    // Books Management & Copy Manager
    Route::resource('books', LibrarianBookController::class);
    Route::post('/books/{book}/add-copy', [LibrarianBookController::class, 'addCopy'])->name('books.add-copy');
    Route::delete('/copies/{copy}', [LibrarianBookController::class, 'deleteCopy'])->name('copies.destroy');

    // Categories Management
    Route::resource('categories', LibrarianCategoryController::class)->except(['create', 'show', 'edit']);

    // Reservations Queue Management
    Route::get('/reservations', [LibrarianReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{reservation}/issue', [LibrarianReservationController::class, 'issue'])->name('reservations.issue');
    Route::post('/reservations/{reservation}/cancel', [LibrarianReservationController::class, 'cancel'])->name('reservations.cancel');

    // Circulation Loans Management & Direct Issue
    Route::get('/loans', [LibrarianLoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LibrarianLoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LibrarianLoanController::class, 'store'])->name('loans.store');
    Route::post('/loans/{loan}/return', [LibrarianLoanController::class, 'returnBook'])->name('loans.return');

    // Fines Tracker & Printable Receipts
    Route::get('/fines', [LibrarianFineController::class, 'index'])->name('fines.index');
    Route::post('/fines/{loan}/pay', [LibrarianFineController::class, 'markPaid'])->name('fines.pay');
    Route::get('/fines/{loan}/receipt', [LibrarianFineController::class, 'receipt'])->name('fines.receipt');
});

/**
 * User Profile Routes
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
