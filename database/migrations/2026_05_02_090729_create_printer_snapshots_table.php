<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printer_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_history_id')->nullable();
            $table->string('printer_name');
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('online');
            $table->integer('total_pages')->default(0);
            $table->integer('cyan_level')->nullable();
            $table->integer('magenta_level')->nullable();
            $table->integer('yellow_level')->nullable();
            $table->integer('black_level')->nullable();
            $table->boolean('auto_email')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamp('imported_at');
            $table->timestamps();

            $table->foreign('import_history_id')
                  ->references('id')->on('import_histories')
                  ->onDelete('set null');

            $table->index(['printer_name', 'imported_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printer_snapshots');
    }
};