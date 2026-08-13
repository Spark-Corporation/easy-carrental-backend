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
        Schema::create('car_backs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->decimal('returnKm', 15, 2);
            $table->enum('fluelLevel', ['Plein','1/4','1/2','3/4' ,'Vide']);
            $table->string('state');
            $table->string('domage')->nullable();
            $table->string('comment')->nullable();
            $table->dateTime('returnDate')->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_backs');
    }
};
