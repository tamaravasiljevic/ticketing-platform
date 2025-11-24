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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_category_id')->nullable()->constrained()->nullOnDelete();

            $table->integer('quantity')->default(1);
            $table->decimal('price_snapshot', 10, 2);
            $table->string('currency', 3)->default('USD');

            $table->json('metadata')->nullable();
            $table->timestamp('reserved_until')->nullable()->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            $table->dropForeign(['ticket_category_id']);
        });
        Schema::dropIfExists('cart_items');
    }
};
