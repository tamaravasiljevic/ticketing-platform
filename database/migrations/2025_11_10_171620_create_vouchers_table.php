<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique(); // npr. GIFT-ABCD1234
            $table->foreignId('purchased_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('initial_value', 10, 2);
            $table->decimal('remaining_value', 10, 2);
            $table->string('currency', 3)->default('USD');

            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->json('metadata')->nullable(); // npr. {"message":"Sretan rođendan!"}

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucheres');
    }
};
