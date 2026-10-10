<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
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
        'status',
        'reserved_at',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user who made the reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reserved book copy.
     */
    public function bookCopy(): BelongsTo
    {
        return $this->belongsTo(BookCopy::class);
    }

    /**
     * Get the associated loan if collected.
     */
    public function loan(): HasOne
    {
        return $this->hasOne(Loan::class);
    }

    /**
     * Scope query to active (pending and unexpired) reservations.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'pending')
                    ->where('expires_at', '>', now());
    }

    /**
     * Check if reservation is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'pending' && $this->expires_at->isFuture();
    }
}
