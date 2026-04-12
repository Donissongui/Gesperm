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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id('id_personnel');

            $table->string('matricule', 50)->unique();
            $table->string('nom', 50)->nullable();
            $table->string('prenom', 50)->nullable();
            $table->string('type_personnel', 50)->nullable();

            $table->foreignId('id_grade')
                ->constrained('grades', 'id_grade')
                ->cascadeOnDelete();

            $table->foreignId('id_brigade')
                ->constrained('brigades', 'id_brigade')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
