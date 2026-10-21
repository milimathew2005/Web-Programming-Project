<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'category_id',
        'publisher',
        'published_year',
        'description',
        'cover_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_year' => 'integer',
        ];
    }

    /**
     * Get the category that owns the book.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all copies of this book.
     */
    public function copies(): HasMany
    {
        return $this->hasMany(BookCopy::class);
    }

    /**
     * Get all reservations for this book through copies.
     */
    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(Reservation::class, BookCopy::class);
    }

    /**
     * Get all loans for this book through copies.
     */
    public function loans(): HasManyThrough
    {
        return $this->hasManyThrough(Loan::class, BookCopy::class);
    }

    /**
     * Count total available copies of this book.
     */
    public function availableCopiesCount(): int
    {
        if (isset($this->attributes['available_copies_count'])) {
            return (int) $this->attributes['available_copies_count'];
        }

        if ($this->relationLoaded('copies')) {
            return $this->copies->where('status', 'available')->count();
        }

        return $this->copies()->where('status', 'available')->count();
    }

    /**
     * Count total copies of this book.
     */
    public function totalCopiesCount(): int
    {
        if (isset($this->attributes['total_copies_count'])) {
            return (int) $this->attributes['total_copies_count'];
        }

        if ($this->relationLoaded('copies')) {
            return $this->copies->count();
        }

        return $this->copies()->count();
    }

    /**
     * Scope query to books with at least one available copy.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereHas('copies', function (Builder $q) {
            $q->where('status', 'available');
        });
    }

    /**
     * Scope query to search books by title, author, ISBN, or category name.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('author', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%")
              ->orWhereHas('category', function (Builder $catQuery) use ($term) {
                  $catQuery->where('name', 'like', "%{$term}%");
              });
        });
    }
}
