<?php

namespace Database\Factories;

use App\Models\BookCopy;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $issuedAt = now()->subDays(fake()->numberBetween(1, 10));
        $dueDate = (clone $issuedAt)->addDays(config('library.default_loan_days', 14));

        return [
            'user_id' => User::factory(),
            'book_copy_id' => BookCopy::factory(),
            'reservation_id' => null,
            'issued_at' => $issuedAt,
            'due_date' => $dueDate,
            'returned_at' => null,
            'fine_amount' => 0.00,
            'fine_paid' => false,
        ];
    }
}
