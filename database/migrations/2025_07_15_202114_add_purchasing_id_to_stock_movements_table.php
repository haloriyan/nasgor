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
            $table->bigInteger('purchasing_id')->unsigned()->index()->nullable()->after('user_id');
            $table->foreign('purchasing_id')->references('id')->on('purchasings')->onDelete('set null');
            $table->integer('total_quantity')->after('type');
            $table->bigInteger('total_price')->after('total_quantity');
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
