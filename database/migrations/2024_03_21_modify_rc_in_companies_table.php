<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, drop the foreign key constraint
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'companies'
            AND COLUMN_NAME = 'id_rc'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($constraints as $constraint) {
            Schema::table('companies', function (Blueprint $table) use ($constraint) {
                $table->dropForeign($constraint->CONSTRAINT_NAME);
            });
        }

        // Then modify the column
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('id_rc');
            $table->string('rc')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('rc');
            $table->unsignedBigInteger('id_rc')->nullable()->after('name');
        });
    }
};
