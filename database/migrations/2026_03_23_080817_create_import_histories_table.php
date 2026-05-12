<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_histories', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->integer('imported_rows')->default(0);
            $table->string('status')->default('success'); // success, failed, processing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_histories');
    }
};