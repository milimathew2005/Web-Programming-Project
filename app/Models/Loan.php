<?php

namespace App\Models;

use App\Services\FineCalculator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'book_copy_id',
        'reservation_id',
        'issued_at',
        'due_date',
        'returned_at',
        'fine_amount',
        'fine_paid',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'due_date' => 'datetime',
            'returned_at' => 'datetime',
            'fine_amount' => 'decimal:2',
            'fine_paid' => 'boolean',
        ];
    }

    /**
     * Get the borrower.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the borrowed copy.
     */
    public function bookCopy(): BelongsTo
    {
        return $this->belongsTo(BookCopy::class);
    }

    /**
     * Get the linked reservation if converted from reservation.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Scope query to active (unreturned) loans.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('returned_at');
    }

    /**
     * Scope query to overdue loans (unreturned and past due date).
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNull('returned_at')
                    ->where('due_date', '<', now());
    }

    /**
     * Check if loan is currently overdue.
     */
    public function isOverdue(): bool
    {
        return $this->daysOverdue() > 0;
    }

    /**
     * Count days overdue (after grace period).
     */
    public function daysOverdue(): int
    {
        return app(FineCalculator::class)->overdueDays($this->due_date, $this->returned_at);
    }

    /**
     * Calculate current calculated fine amount.
     */
    public function currentFine(): float
    {
        return calculate_fine($this->due_date, $this->returned_at);
    }
}
