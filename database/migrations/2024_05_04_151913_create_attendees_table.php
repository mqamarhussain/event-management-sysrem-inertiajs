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
        Schema::create('attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('customer_id');
            $table->text('content')->nullable();
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->tinyInteger('payment_status')->default(0)->comment('0= pending, 1=Paid');
            $table->float('amount_paid')->default(0);
            $table->tinyInteger('status')->default(0)->comment('0= for verification, 1= confirmed, 2= declined');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendees');
    }
};
