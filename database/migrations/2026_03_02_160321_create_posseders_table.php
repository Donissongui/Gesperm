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
        Schema::create('posseders', function (Blueprint $table) {

            $table->foreignId('id_personnel')
                ->constrained('personnels', 'id_personnel')
                ->cascadeOnDelete();

            $table->foreignId('id_permission')
                ->constrained('permissions', 'id_permission')
                ->cascadeOnDelete();

            $table->date('date_début');
            $table->date('date_fin')->nullable();
            $table->text('motif')->nullable();
            $table->string('destination', 50)->nullable();
            $table->string('avis', 50)->nullable();

            $table->primary(['id_personnel', 'id_permission']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posseders');
    }
};
