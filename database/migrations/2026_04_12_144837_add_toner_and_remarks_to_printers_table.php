<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            if (!Schema::hasColumn('printers', 'cyan_level'))
                $table->integer('cyan_level')->nullable()->after('total_pages');
            if (!Schema::hasColumn('printers', 'magenta_level'))
                $table->integer('magenta_level')->nullable()->after('cyan_level');
            if (!Schema::hasColumn('printers', 'yellow_level'))
                $table->integer('yellow_level')->nullable()->after('magenta_level');
            if (!Schema::hasColumn('printers', 'black_level'))
                $table->integer('black_level')->nullable()->after('yellow_level');
            if (!Schema::hasColumn('printers', 'auto_email'))
                $table->boolean('auto_email')->default(false)->after('black_level');
            if (!Schema::hasColumn('printers', 'remarks'))
                $table->text('remarks')->nullable()->after('auto_email');
        });
    }

    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn(['cyan_level','magenta_level','yellow_level','black_level','auto_email','remarks']);
        });
    }
};