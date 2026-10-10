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

class StudentFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test catalog search and category filtering.
     */
    public function test_catalog_search_and_category_filter(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $catCs = Category::factory()->create(['name' => 'Computer Science']);
        $catLit = Category::factory()->create(['name' => 'Literature']);

        $bookCs = Book::factory()->create([
            'title' => 'Laravel Architecture and Design',
            'category_id' => $catCs->id,
        ]);
        BookCopy::factory()->create(['book_id' => $bookCs->id, 'status' => 'available']);

        $bookLit = Book::factory()->create([
            'title' => 'Classic Poetry Collection',
            'category_id' => $catLit->id,
        ]);
        BookCopy::factory()->create(['book_id' => $bookLit->id, 'status' => 'available']);

        // Test search query
        $response = $this->actingAs($student)->get('/books?search=Laravel');
        $response->assertStatus(200);
        $response->assertSee('Laravel Architecture and Design');
        $response->assertDontSee('Classic Poetry Collection');

        // Test category filter query
        $response = $this->actingAs($student)->get('/books?category_id=' . $catLit->id);
        $response->assertStatus(200);
        $response->assertSee('Classic Poetry Collection');
        $response->assertDontSee('Laravel Architecture and Design');
    }

    /**
     * Test successful book reservation inside DB transaction.
     */
    public function test_reserve_success(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create(['title' => 'Clean Code']);
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'available']);

        $response = $this->actingAs($student)->post("/books/{$book->id}/reserve");

        $response->assertRedirect('/student/reservations');
        $response->assertSessionHas('success');

        // Assert database updates
        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'reserved',
        ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $student->id,
            'book_copy_id' => $copy->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test reservation failure when no copies are available.
     */
    public function test_reserve_failure_when_unavailable(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create(['title' => 'Unavailable Book']);
        BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $response = $this->actingAs($student)->post("/books/{$book->id}/reserve");

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('reservations', 0);
    }

    /**
     * Test reservation failure when user active borrow limit (3 items) is reached.
     */
    public function test_reserve_failure_when_limit_enforced(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create(['title' => 'Target Book']);
        BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'available']);

        // Fill student active limit with 3 loans
        for ($i = 0; $i < 3; $i++) {
            $otherCopy = BookCopy::factory()->create(['status' => 'borrowed']);
            Loan::factory()->create([
                'user_id' => $student->id,
                'book_copy_id' => $otherCopy->id,
                'returned_at' => null,
            ]);
        }

        $response = $this->actingAs($student)->post("/books/{$book->id}/reserve");

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('reservations', 0);
    }

    /**
     * Test cancelling a reservation frees the reserved copy back to available status.
     */
    public function test_cancel_reservation_frees_copy(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $book = Book::factory()->create();
        $copy = BookCopy::factory()->create(['book_id' => $book->id, 'status' => 'reserved']);

        $reservation = Reservation::factory()->create([
            'user_id' => $student->id,
            'book_copy_id' => $copy->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)->post("/student/reservations/{$reservation->id}/cancel");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('book_copies', [
            'id' => $copy->id,
            'status' => 'available',
        ]);
    }
}
