<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseLayerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Book availableCopiesCount and available scope.
     */
    public function test_book_available_copies_count_and_scope(): void
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'available']);
        BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'available']);
        BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $this->assertEquals(2, $book->availableCopiesCount());
        $this->assertEquals(3, $book->totalCopiesCount());

        $availableBooks = Book::available()->get();
        $this->assertTrue($availableBooks->contains($book));
    }

    /**
     * Test User active borrow limit rule (max 3 active reservations + loans).
     */
    public function test_user_active_borrow_limit_business_rule(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $this->assertTrue($student->canReserve());

        // Create 3 active loans/reservations
        for ($i = 0; $i < 3; $i++) {
            $copy = BookCopy::factory()->create(['status' => 'borrowed']);
            Loan::factory()->create([
                'user_id' => $student->id,
                'book_copy_id' => $copy->id,
                'returned_at' => null,
            ]);
        }

        $this->assertEquals(3, $student->activeBorrowCount());
        $this->assertFalse($student->canReserve());
    }

    /**
     * Test that user cannot reserve two copies of the same book.
     */
    public function test_user_cannot_reserve_two_copies_of_same_book(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create();
        $copy1 = BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'reserved']);

        Reservation::factory()->create([
            'user_id' => $student->id,
            'book_copy_id' => $copy1->id,
            'status' => 'pending',
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertTrue($student->hasActiveReservationOrLoanForBook($book->id));
    }
}
