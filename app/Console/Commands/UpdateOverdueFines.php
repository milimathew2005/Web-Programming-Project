<?php

namespace App\Console\Commands;

use App\Models\Loan;
use Illuminate\Console\Command;

class UpdateOverdueFines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:update-fines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate and update fine_amount for all unreturned overdue loans';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $overdueLoans = Loan::active()->overdue()->get();
        $updatedCount = 0;

        foreach ($overdueLoans as $loan) {
            $calculatedFine = $loan->currentFine();
            if ((float) $loan->fine_amount !== $calculatedFine) {
                $loan->update(['fine_amount' => $calculatedFine]);
                $updatedCount++;
            }
        }

        $this->info("Updated fine amounts for {$updatedCount} overdue loan(s).");
        return Command::SUCCESS;
    }
}
