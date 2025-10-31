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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('address_complement')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->enum('payment_status', ['confirmed', 'pending', 'awaiting_payment'])->default('awaiting_payment')->nullable();
            $table->enum('payment_method', ['cash', 'pix', 'credit_card', 'debit_card', 'bank_slip', 'crypto_currency'])->nullable();
            $table->integer('due_day')->nullable();
            $table->integer('assembly_day')->nullable();
            $table->decimal('credit_amount', 15, 2)->nullable();
            $table->enum('situation', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->foreign('lead_id')->references('id')->on('leads');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_clients');
    }
};
