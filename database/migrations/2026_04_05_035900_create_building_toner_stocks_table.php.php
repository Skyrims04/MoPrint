<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('building_toner_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->string('type');
            $table->string('color_hex', 7)->default('#64748b');
            $table->integer('stock_qty')->default(0);
            $table->integer('threshold')->default(5);
            $table->timestamps();

            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->unique(['building_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('building_toner_stocks');
    }
};