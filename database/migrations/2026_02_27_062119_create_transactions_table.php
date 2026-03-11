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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // user_id removed because transactions are not tied to a user
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->enum('type', ['IN', 'OUT', 'TRANS']); // IN: Masuk, OUT: Keluar, TRANS: Transfer
            $table->decimal('amount', 15, 2);
            $table->text('note')->nullable();
            $table->foreignId('from_wallet_id')->nullable()->constrained('wallets')->onDelete('set null');
            $table->foreignId('to_wallet_id')->nullable()->constrained('wallets')->onDelete('set null');
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
