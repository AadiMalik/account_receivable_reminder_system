<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id')->nullable();
            $table->integer('old_invoice_detail_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(0.00);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('tax_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('old_invoice_id')->nullable();
            $table->integer('old_company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
};
