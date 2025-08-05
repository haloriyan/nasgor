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
        Schema::create('sales_item_addons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('item_id')->unsigned()->index();
            $table->foreign('item_id')->references('id')->on('sales_items')->onDelete('cascade');
            $table->bigInteger('addon_id')->unsigned()->index();
            $table->foreign('addon_id')->references('id')->on('add_ons')->onDelete('cascade');
            $table->bigInteger('price');
            $table->integer('quantity');
            $table->bigInteger('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_item_addons');
    }
};
