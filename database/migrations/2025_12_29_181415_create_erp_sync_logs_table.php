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
        Schema::create('erp_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();
            $table->integer('customers_added')->default(0);
            $table->integer('customers_updated')->default(0);
            $table->integer('invoices_added')->default(0);
            $table->integer('invoices_updated')->default(0);
            $table->integer('invoice_items_added')->default(0);
            $table->integer('invoice_items_updated')->default(0);
            $table->timestamp('synced_at')->nullable();
            $table->integer('createdby_id')->nullable();
            $table->integer('updatedby_id')->nullable();
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
        Schema::dropIfExists('erp_sync_logs');
    }
};
