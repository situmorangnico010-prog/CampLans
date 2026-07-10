<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sewa', 20)->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_amount', 12, 2);
            $table->text('note')->nullable();
            $table->string('status', 20)->default('pending');
            $table->date('actual_return_date')->nullable();
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->string('payment_status', 30)->default('unpaid');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('rentals');
    }
};