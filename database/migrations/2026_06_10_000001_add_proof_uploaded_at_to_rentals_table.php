<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'proof_uploaded_at')) {
                $table->timestamp('proof_uploaded_at')->nullable()->after('payment_proof');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'proof_uploaded_at')) {
                $table->dropColumn('proof_uploaded_at');
            }
        });
    }
};
