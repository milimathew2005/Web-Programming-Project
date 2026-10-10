<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookCopy>
 */
class BookCopyFactory extends Factory
{
    protected $model = BookCopy::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'copy_code' => 'BK' . fake()->unique()->numberBetween(1000, 9999) . '-C' . fake()->numberBetween(1, 9),
            'status' => 'available',
        ];
    }
}
