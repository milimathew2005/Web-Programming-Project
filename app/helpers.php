<?php

use App\Services\FineCalculator;
use Carbon\Carbon;

if (! function_exists('calculate_fine')) {
    /**
     * Configurable PHP fine calculation function.
     * Calculates library overdue fines based on due date, return date, and rate configuration.
     *
     * @param  Carbon  $dueDate  The due date for the loan.
     * @param  Carbon|null  $returnedAt  Optional return date (defaults to current time if unreturned).
     * @param  array  $customConfig  Optional rate configuration overrides.
     * @return float Calculated overdue fine amount.
     */
    function calculate_fine(Carbon $dueDate, ?Carbon $returnedAt = null, array $customConfig = []): float
    {
        return app(FineCalculator::class)->calculate($dueDate, $returnedAt, $customConfig);
    }
}
