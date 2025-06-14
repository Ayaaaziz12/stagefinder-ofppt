<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_company')->constrained('companies')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('salary');
            $table->string('type');
            $table->string('location');
            $table->json('requirements');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_listings');
    }
};
