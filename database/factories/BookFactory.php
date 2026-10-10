<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'isbn' => fake()->unique()->isbn13(),
            'category_id' => Category::factory(),
            'publisher' => fake()->company(),
            'published_year' => fake()->numberBetween(1995, 2025),
            'description' => fake()->paragraph(),
            'cover_image' => null,
        ];
    }
}
