<?php

namespace Database\Factories;

use App\Models\BookCopy;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $reservedAt = now()->subHours(fake()->numberBetween(1, 24));
        $expiresAt = (clone $reservedAt)->addHours(config('library.reservation_expiry_hours', 48));

        return [
            'user_id' => User::factory(),
            'book_copy_id' => BookCopy::factory(),
            'status' => 'pending',
            'reserved_at' => $reservedAt,
            'expires_at' => $expiresAt,
        ];
    }
}
