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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('ville')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('password');
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('rc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
