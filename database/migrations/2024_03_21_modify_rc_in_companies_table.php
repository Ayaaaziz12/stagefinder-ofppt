<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Only drop the column if it exists
        if (Schema::hasColumn('companies', 'id_rc')) {
            // First, drop the foreign key constraint if it exists
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

            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('id_rc');
            });
        }
        // Then add the rc column if it doesn't exist
        if (!Schema::hasColumn('companies', 'rc')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('rc')->nullable()->after('name');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('companies', 'rc')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('rc');
            });
        }
        if (!Schema::hasColumn('companies', 'id_rc')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->unsignedBigInteger('id_rc')->nullable()->after('name');
            });
        }
    }
};
