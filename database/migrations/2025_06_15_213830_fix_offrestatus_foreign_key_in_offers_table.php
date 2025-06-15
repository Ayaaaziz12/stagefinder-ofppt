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
        Schema::table('offers', function (Blueprint $table) {
            // Only add the correct foreign key constraint
            $table->unsignedBigInteger('id_OffreStatus')->change();
            $table->foreign('id_OffreStatus')->references('id')->on('offrestatuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign(['id_OffreStatus']);
            // Optionally, you could add the old foreign key back here if needed
        });
    }
};
