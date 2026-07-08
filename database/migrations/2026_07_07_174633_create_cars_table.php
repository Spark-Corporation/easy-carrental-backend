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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('mark');
            $table->string('type');
            $table->string('model');
            $table->string('color');
            $table->string('photo');
            $table->string('imatriculation')->unique();
            $table->string('description')->nullable();
            $table->double('prix_km');
            $table->string('state');
            $table->integer('place');
            $table->integer('door');
            $table->double('kilometrage');
            $table->double('niveauCarburant');
            $table->string('domage')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
