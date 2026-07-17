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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->datetime('dateStart')->default(now());
            $table->datetime('dateBack')->nullable();
            $table->decimal('driverAmount', 10, 2)->nullable();
            $table->enum('type', ['reservation', 'leasing'])->default('reservation');
            $table->enum('status', ['En attente', 'validé', 'annulée', 'refusée', 'en cours', 'terminée'])->default('En attente');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
