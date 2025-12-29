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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('old_invoice_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('invoice_series')->nullable();
            $table->string('invoice_type')->nullable();
            $table->string('invoice_number')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->decimal('total_amount',18,2)->default(0);
            $table->integer('old_company_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->integer('credit_days')->nullable();
            $table->dateTime('registration_date')->nullable();
            $table->integer('status_id')->nullable();
            $table->string('document_type')->nullable();
            $table->dateTime('document_date')->nullable();
            $table->integer('iva')->nullable();
            $table->decimal('idp',18,2)->nullable();
            $table->decimal('oi',18,2)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('erp_uuid')->nullable();
            $table->string('erp_uuid_series')->nullable();
            $table->string('erp_invoice_number')->nullable();
            $table->integer('currency')->nullable();
            $table->decimal('paid_amount',18,2)->default(0);
            $table->decimal('exchange_rate',18,2)->nullable();
            $table->string('fel_address')->nullable();
            $table->dateTime('cancellation_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('company_id')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
