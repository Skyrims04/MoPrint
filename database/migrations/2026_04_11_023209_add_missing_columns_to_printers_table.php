<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            if (!Schema::hasColumn('printers', 'name'))
                $table->string('name')->after('id');
            if (!Schema::hasColumn('printers', 'model'))
                $table->string('model')->nullable()->after('name');
            if (!Schema::hasColumn('printers', 'location'))
                $table->string('location')->nullable()->after('serial_number');
            if (!Schema::hasColumn('printers', 'ip_address'))
                $table->string('ip_address')->nullable()->after('location');
            if (!Schema::hasColumn('printers', 'status'))
                $table->string('status')->default('offline')->after('ip_address');
            if (!Schema::hasColumn('printers', 'pages_today'))
                $table->integer('pages_today')->default(0)->after('status');
            if (!Schema::hasColumn('printers', 'total_pages'))
                $table->integer('total_pages')->default(0)->after('pages_today');
            if (!Schema::hasColumn('printers', 'last_seen_at'))
                $table->timestamp('last_seen_at')->nullable()->after('total_pages');
        });
    }

    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn(['name', 'model', 'location', 'ip_address', 'status', 'pages_today', 'total_pages', 'last_seen_at']);
        });
    }
};