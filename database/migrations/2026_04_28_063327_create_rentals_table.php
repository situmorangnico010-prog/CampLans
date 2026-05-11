<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->date('actual_return_date')->nullable();
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('rentals');
    }
};