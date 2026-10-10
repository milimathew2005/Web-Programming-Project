<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Library System Fine & Borrow Config
    |--------------------------------------------------------------------------
    |
    | Read environment settings with configurable defaults for student viva & demo.
    |
    */

    'loan_days' => (int) env('LIBRARY_LOAN_DAYS', 14),
    'fine_per_day' => (float) env('LIBRARY_FINE_PER_DAY', 2.00),
    'grace_days' => (int) env('LIBRARY_GRACE_DAYS', 0),
    'max_fine' => (float) env('LIBRARY_MAX_FINE', 500.00),
    'max_active_items_per_user' => (int) env('LIBRARY_MAX_ACTIVE_ITEMS', 3),
    'reservation_hold_hours' => (int) env('LIBRARY_RESERVATION_HOLD_HOURS', 48),
    'currency_symbol' => env('LIBRARY_CURRENCY_SYMBOL', '₹'),

    // Aliases for backward compatibility
    'max_active_borrow_limit' => (int) env('LIBRARY_MAX_ACTIVE_ITEMS', 3),
    'reservation_expiry_hours' => (int) env('LIBRARY_RESERVATION_HOLD_HOURS', 48),
    'default_loan_days' => (int) env('LIBRARY_LOAN_DAYS', 14),
    'daily_fine_rate' => (float) env('LIBRARY_FINE_PER_DAY', 2.00),
];
