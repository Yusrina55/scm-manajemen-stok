<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receiving_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->integer('supplier_price');
            $table->timestamp('entry_date')->useCurrent();
            $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('supplier_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receiving_logs');
    }
};
