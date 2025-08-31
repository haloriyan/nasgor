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
        Schema::table('product_ingredients', function (Blueprint $table) {
            $table->bigInteger('variant_id')->nullable()->after('id')->unsigned()->index();
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('set null');
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_ingredients', function (Blueprint $table) {
            //
        });
    }
};
