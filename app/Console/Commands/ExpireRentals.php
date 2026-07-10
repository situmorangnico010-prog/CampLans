<?php

namespace App\Console\Commands;

use App\Models\Rental;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireRentals extends Command
{
    protected $signature   = 'rentals:expire';
    protected $description = 'Batalkan secara otomatis rental yang melewati batas waktu pembayaran';

    public function handle(): int
    {
        $expired = Rental::expired()->with('details.item')->get();

        if ($expired->isEmpty()) {
            $this->info('Tidak ada rental yang kedaluwarsa.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($expired as $rental) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($rental) {
                // Kembalikan stok item
                foreach ($rental->details as $detail) {
                    $detail->item->increment('stock', $detail->quantity);
                }

                $rental->update([
                    'transaction_status' => 'expired',
                    'payment_status'     => 'unpaid',
                    'status'             => 'cancelled',
                ]);
            });

            Log::info("Rental #{$rental->kode_sewa} kedaluwarsa dan dibatalkan otomatis.");
            $count++;
        }

        $this->info("✅ {$count} rental berhasil dibatalkan karena kedaluwarsa.");
        return self::SUCCESS;
    }
}
