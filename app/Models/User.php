<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
    ];

    /**
     * Check if user is a librarian.
     */
    public function isLibrarian(): bool
    {
        return $this->role === 'librarian';
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Get user's reservations.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get user's book loans.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Count active (pending unexpired) reservations.
     */
    public function activeReservationsCount(): int
    {
        return $this->reservations()
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->count();
    }

    /**
     * Count active (unreturned) loans.
     */
    public function activeLoansCount(): int
    {
        return $this->loans()
            ->whereNull('returned_at')
            ->count();
    }

    /**
     * Combined total of active reservations and active loans.
     */
    public function activeBorrowCount(): int
    {
        return $this->activeReservationsCount() + $this->activeLoansCount();
    }

    /**
     * Check if user can place a new reservation (under limit).
     */
    public function canReserve(): bool
    {
        $maxLimit = config('library.max_active_borrow_limit', 3);
        return $this->activeBorrowCount() < $maxLimit;
    }

    /**
     * Check if user already holds an active reservation or unreturned loan for the specified book.
     */
    public function hasActiveReservationOrLoanForBook(int $bookId): bool
    {
        $hasActiveReservation = $this->reservations()
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->whereHas('bookCopy', function ($query) use ($bookId) {
                $query->where('book_id', $bookId);
            })->exists();

        if ($hasActiveReservation) {
            return true;
        }

        $hasActiveLoan = $this->loans()
            ->whereNull('returned_at')
            ->whereHas('bookCopy', function ($query) use ($bookId) {
                $query->where('book_id', $bookId);
            })->exists();

        return $hasActiveLoan;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
