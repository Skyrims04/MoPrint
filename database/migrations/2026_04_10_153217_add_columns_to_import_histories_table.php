<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('import_histories', 'file_name')) {
                $table->string('file_name')->after('id');
            }
            if (!Schema::hasColumn('import_histories', 'imported_rows')) {
                $table->integer('imported_rows')->default(0)->after('file_name');
            }
            if (!Schema::hasColumn('import_histories', 'status')) {
                $table->string('status')->default('success')->after('imported_rows');
            }
        });
    }

    public function down(): void
    {
        Schema::table('import_histories', function (Blueprint $table) {
            $table->dropColumn(['file_name', 'imported_rows', 'status']);
        });
    }
};