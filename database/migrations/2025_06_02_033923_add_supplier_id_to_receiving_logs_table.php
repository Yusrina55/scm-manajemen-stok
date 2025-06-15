<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierIdToReceivingLogsTable extends Migration
{
    public function up(): void
    {
        Schema::table('receiving_logs', function (Blueprint $table) {
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('receiving_logs', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
}