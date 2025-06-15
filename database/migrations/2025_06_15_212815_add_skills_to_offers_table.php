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
        if (!Schema::hasColumn('offers', 'skills')) {
            Schema::table('offers', function (Blueprint $table) {
                $table->text('skills')->nullable()->after('Job_Descriptin');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('offers', 'skills')) {
            Schema::table('offers', function (Blueprint $table) {
                $table->dropColumn('skills');
            });
        }
    }
};
