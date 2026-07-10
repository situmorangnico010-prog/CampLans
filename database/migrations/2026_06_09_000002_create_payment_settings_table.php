<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed nilai default
        $defaults = [
            ['key' => 'bank_name',       'value' => 'BCA'],
            ['key' => 'account_number',  'value' => '1234567890'],
            ['key' => 'account_name',    'value' => 'CampLens Store'],
            ['key' => 'qris_image',      'value' => null],
            ['key' => 'payment_hours',   'value' => '24'],
            ['key' => 'ewallet_name',    'value' => 'GoPay'],
            ['key' => 'ewallet_number',  'value' => '081234567890'],
            ['key' => 'penalty_per_day', 'value' => '50000'],
        ];

        foreach ($defaults as $row) {
            DB::table('payment_settings')->insert(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
