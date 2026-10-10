<?php

namespace App\Services;

use Carbon\Carbon;

class FineCalculator
{
    /**
     * Calculate overdue fine based on due date, return date, and config rules.
     *
     * @param  Carbon  $dueDate  The date when the book was due.
     * @param  Carbon|null  $returnedAt  The date when book was returned (defaults to now() if null).
     * @param  array  $customConfig  Optional rate overrides for unit testing.
     * @return float Calculated fine amount capped at max_fine, never negative.
     */
    public function calculate(Carbon $dueDate, ?Carbon $returnedAt = null, array $customConfig = []): float
    {
        $finePerDay = (float) ($customConfig['fine_per_day'] ?? config('library.fine_per_day', 2.00));
        $graceDays = (int) ($customConfig['grace_days'] ?? config('library.grace_days', 0));
        $maxFine = (float) ($customConfig['max_fine'] ?? config('library.max_fine', 500.00));

        $endDate = $returnedAt ? $returnedAt->copy() : now();
        $due = $dueDate->copy();

        // If returned/current date is on or before due date, no fine
        if ($endDate->lessThanOrEqualTo($due)) {
            return 0.00;
        }

        // Calculate total overdue days using day boundary comparison
        $totalOverdueDays = (int) $due->startOfDay()->diffInDays($endDate->startOfDay(), false);

        if ($totalOverdueDays <= 0) {
            return 0.00;
        }

        // Subtract grace days
        $chargeableDays = $totalOverdueDays - $graceDays;

        if ($chargeableDays <= 0) {
            return 0.00;
        }

        $calculatedFine = $chargeableDays * $finePerDay;

        // Cap fine at max_fine and ensure non-negative value
        $finalFine = min($calculatedFine, $maxFine);

        return round(max(0.00, $finalFine), 2);
    }

    /**
     * Calculate the number of chargeable overdue days after grace period.
     */
    public function overdueDays(Carbon $dueDate, ?Carbon $returnedAt = null, array $customConfig = []): int
    {
        $graceDays = (int) ($customConfig['grace_days'] ?? config('library.grace_days', 0));
        $endDate = $returnedAt ? $returnedAt->copy() : now();
        $due = $dueDate->copy();

        if ($endDate->lessThanOrEqualTo($due)) {
            return 0;
        }

        $totalOverdueDays = (int) $due->startOfDay()->diffInDays($endDate->startOfDay(), false);
        $chargeableDays = $totalOverdueDays - $graceDays;

        return max(0, $chargeableDays);
    }
}
