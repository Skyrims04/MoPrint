<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('toner_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('printer_id')->constrained()->onDelete('cascade');
            $table->string('type');           // Cyan, Magenta, Yellow, Black
            $table->string('color_hex')->nullable(); // #00bcd4, #e91e63, dst
            $table->integer('level')->default(100);  // 0-100 persen
            $table->integer('stock_qty')->default(0); // jumlah unit fisik
            $table->integer('threshold')->default(5); // batas minimum stok
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toner_stocks');
    }
};