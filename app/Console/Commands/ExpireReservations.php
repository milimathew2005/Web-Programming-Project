<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:expire-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending reservations past expires_at date and release reserved book copies';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredReservations = Reservation::where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->with('bookCopy')
            ->get();

        $count = 0;

        foreach ($expiredReservations as $reservation) {
            DB::transaction(function () use ($reservation) {
                $reservation->update(['status' => 'expired']);

                if ($reservation->bookCopy && $reservation->bookCopy->status === 'reserved') {
                    $reservation->bookCopy->update(['status' => 'available']);
                }
            });

            $count++;
        }

        $this->info("Expired {$count} reservation(s) and restored copy availability.");
        return Command::SUCCESS;
    }
}
