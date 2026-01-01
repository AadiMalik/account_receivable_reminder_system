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
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('document_type')->nullable(); 
            $table->string('document_number')->nullable(); 
            $table->string('document_series')->nullable(); 
            $table->string('customer_code')->nullable(); 
            $table->date('issue_date')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('balance_amount', 10, 2)->default(0);
            $table->integer('credit_days')->default(0);
            $table->date('due_date')->nullable(); 
            $table->string('payment_type')->nullable();
            $table->string('concept')->nullable(); 
            $table->string('erp_uuid')->nullable(); 
            $table->string('erp_series')->nullable(); 
            $table->string('erp_document_number')->nullable();
            $table->integer('old_company_id')->nullable();
            $table->integer('customer_id')->nullable();
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
        Schema::dropIfExists('customer_invoices');
    }
};
