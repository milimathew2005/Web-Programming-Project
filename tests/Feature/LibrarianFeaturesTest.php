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

class LibrarianFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test issuing a book from a student's pending reservation.
     */
    public function test_issue_book_from_reservation(): void
    {
        $librarian = User::factory()->librarian()->create();
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create();
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'reserved']);

        $reservation = Reservation::factory()->create([
            'user_id' => $student->id,
            'book_copy_id' => $copy->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($librarian)
            ->post("/librarian/reservations/{$reservation->id}/issue");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'collected',
        ]);

        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'borrowed',
        ]);

        $this->assertDatabaseHas('loans', [
            'user_id' => $student->id,
            'book_copy_id' => $copy->id,
            'reservation_id' => $reservation->id,
            'returned_at' => null,
        ]);
    }

    /**
     * Test direct book issue without prior online reservation.
     */
    public function test_direct_issue_book(): void
    {
        $librarian = User::factory()->librarian()->create();
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create();
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'available']);

        $response = $this->actingAs($librarian)
            ->post('/librarian/loans', [
                'user_id' => $student->id,
                'book_copy_id' => $copy->id,
            ]);

        $response->assertRedirect('/librarian/loans');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'borrowed',
        ]);

        $this->assertDatabaseHas('loans', [
            'user_id' => $student->id,
            'book_copy_id' => $copy->id,
        ]);
    }

    /**
     * Test returning an overdue loan and calculating final fine.
     */
    public function test_return_book_with_fine_calculation(): void
    {
        $librarian = User::factory()->librarian()->create();
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create();
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $issuedAt = now()->subDays(20);
        $dueDate = now()->subDays(6); // 6 days overdue

        $loan = Loan::factory()->create([
            'user_id' => $student->id,
            'book_copy_id' => $copy->id,
            'issued_at' => $issuedAt,
            'due_date' => $dueDate,
            'returned_at' => null,
            'fine_amount' => 0.00,
        ]);

        $response = $this->actingAs($librarian)
            ->post("/librarian/loans/{$loan->id}/return");

        $response->assertSessionHas('success');

        $loan->refresh();
        $this->assertNotNull($loan->returned_at);
        $this->assertEquals(12.00, (float) $loan->fine_amount); // 6 days * $2.00

        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'available',
        ]);
    }

    /**
     * Test marking a fine as paid by librarian.
     */
    public function test_mark_fine_as_paid(): void
    {
        $librarian = User::factory()->librarian()->create();
        $student = User::factory()->create(['role' => 'student']);
        $copy = BookCopy::factory()->create(['status' => 'available']);

        $loan = Loan::factory()->create([
            'user_id' => $student->id,
            'book_copy_id' => $copy->id,
            'due_date' => now()->subDays(5),
            'returned_at' => now()->subDays(1),
            'fine_amount' => 8.00,
            'fine_paid' => false,
        ]);

        $response = $this->actingAs($librarian)
            ->post("/librarian/fines/{$loan->id}/pay");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'fine_paid' => true,
        ]);
    }

    /**
     * Test preventing deletion of a book with active borrowed copies.
     */
    public function test_prevent_deleting_book_with_active_loans(): void
    {
        $librarian = User::factory()->librarian()->create();
        $book = Book::factory()->create();
        BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $response = $this->actingAs($librarian)
            ->delete("/librarian/books/{$book->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }
}
