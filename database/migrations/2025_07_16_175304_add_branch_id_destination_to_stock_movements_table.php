<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->bigInteger('branch_id_destination')->unsigned()->index()->nullable()->after('branch_id');
            $table->foreign('branch_id_destination')->references('id')->on('branches')->onDelete('set null');
            $table->bigInteger('movement_id_ref')->unsigned()->index()->nullable()->after('id'); // untuk branch yang menerima
            $table->foreign('movement_id_ref')->references('id')->on('stock_movements')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            //
        });
    }
};
