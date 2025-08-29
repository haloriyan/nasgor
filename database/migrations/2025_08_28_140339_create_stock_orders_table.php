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
        Schema::create('stock_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('seeker_branch_id')->unsigned()->index()->nullable();
            $table->foreign('seeker_branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->bigInteger('seeker_id')->unsigned()->index();
            $table->foreign('seeker_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('taker_id')->unsigned()->index()->nullable();
            $table->foreign('taker_id')->references('id')->on('users')->onDelete('set null');
            $table->bigInteger('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_orders');
    }
};
