<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Status transaksi yang lebih granular (menggantikan 'status' lama)
            $table->string('transaction_status', 30)->default('pending')->after('status');

            // Metode pembayaran
            $table->string('payment_method', 30)->nullable()->after('payment_status');

            // Bukti pembayaran (path file)
            $table->string('payment_proof', 150)->nullable()->after('payment_method');

            // Catatan verifikasi dari admin
            $table->text('payment_note')->nullable()->after('payment_proof');

            // Admin yang memverifikasi
            $table->unsignedBigInteger('verified_by')->nullable()->after('payment_note');
            $table->timestamp('verified_at')->nullable()->after('verified_by');

            // Waktu pengembalian aktual
            $table->timestamp('returned_at')->nullable()->after('verified_at');

            // Batas waktu pembayaran (24 jam setelah booking)
            $table->timestamp('expired_at')->nullable()->after('returned_at');

            // Foreign key untuk verified_by
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'transaction_status',
                'payment_method',
                'payment_proof',
                'payment_note',
                'verified_by',
                'verified_at',
                'returned_at',
                'expired_at',
            ]);
        });
    }
};
