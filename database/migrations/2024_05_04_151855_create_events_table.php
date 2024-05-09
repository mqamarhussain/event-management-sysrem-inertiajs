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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->foreign('venue_id')->references('id')->on('venues')->cascadeOnDelete();
            $table->unsignedBigInteger('promoter_id');
            $table->foreign('promoter_id')->references('id')->on('users')->cascadeOnDelete();
            $table->text('event');
            $table->text('description');
            $table->dateTime('event_date');
            $table->tinyInteger('type')->default(1)->comment('1=Public, 2=Private');
            $table->integer('audience_capacity');
            $table->tinyInteger('payment_type')->default(1)->comment('1=Free, 2=Payable');
            $table->double('amount')->default(0);
            $table->text('banner');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
