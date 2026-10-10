<?php

namespace Tests\Unit;

use App\Services\FineCalculator;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class FineCalculatorTest extends TestCase
{
    private FineCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new FineCalculator();
    }

    /**
     * Test case 1: No fine when returned or checked on or before due date.
     */
    public function test_no_fine_within_due_date(): void
    {
        $dueDate = Carbon::now()->addDays(5);
        $returnedAt = Carbon::now();

        $fine = $this->calculator->calculate($dueDate, $returnedAt, [
            'fine_per_day' => 2.00,
            'grace_days' => 0,
            'max_fine' => 500.00,
        ]);

        $this->assertEquals(0.00, $fine);
    }

    /**
     * Test case 2: No fine within grace period.
     */
    public function test_within_grace_period(): void
    {
        $dueDate = Carbon::now()->subDays(2);
        $returnedAt = Carbon::now();

        // 2 days overdue, but 3 grace days allowed
        $fine = $this->calculator->calculate($dueDate, $returnedAt, [
            'fine_per_day' => 5.00,
            'grace_days' => 3,
            'max_fine' => 500.00,
        ]);

        $this->assertEquals(0.00, $fine);
    }

    /**
     * Test case 3: Normal overdue fine calculation.
     */
    public function test_normal_overdue_calculation(): void
    {
        $dueDate = Carbon::now()->subDays(5);
        $returnedAt = Carbon::now();

        // 5 days overdue * $2.00/day = $10.00
        $fine = $this->calculator->calculate($dueDate, $returnedAt, [
            'fine_per_day' => 2.00,
            'grace_days' => 0,
            'max_fine' => 500.00,
        ]);

        $this->assertEquals(10.00, $fine);
    }

    /**
     * Test case 4: Max fine cap enforcement.
     */
    public function test_max_fine_cap_enforcement(): void
    {
        $dueDate = Carbon::now()->subDays(100);
        $returnedAt = Carbon::now();

        // 100 days * $10.00 = $1000.00, but max_fine cap is $50.00
        $fine = $this->calculator->calculate($dueDate, $returnedAt, [
            'fine_per_day' => 10.00,
            'grace_days' => 0,
            'max_fine' => 50.00,
        ]);

        $this->assertEquals(50.00, $fine);
    }

    /**
     * Test case 5: Custom configuration parameters override.
     */
    public function test_custom_config_override(): void
    {
        $dueDate = Carbon::now()->subDays(4);
        $returnedAt = Carbon::now();

        // 4 days overdue - 1 grace day = 3 chargeable days * $7.50 = $22.50
        $fine = $this->calculator->calculate($dueDate, $returnedAt, [
            'fine_per_day' => 7.50,
            'grace_days' => 1,
            'max_fine' => 100.00,
        ]);

        $this->assertEquals(22.50, $fine);
    }

    /**
     * Test case 6: Returned late case with explicit returnedAt timestamp.
     */
    public function test_returned_late_case(): void
    {
        $dueDate = Carbon::parse('2026-09-01 10:00:00');
        $returnedAt = Carbon::parse('2026-09-05 14:00:00'); // 4 days late

        // 4 days late * $2.00/day = $8.00
        $fine = $this->calculator->calculate($dueDate, $returnedAt, [
            'fine_per_day' => 2.00,
            'grace_days' => 0,
            'max_fine' => 500.00,
        ]);

        $this->assertEquals(8.00, $fine);
    }
}
