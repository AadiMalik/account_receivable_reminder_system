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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('green_api_instance')->nullable();
            $table->string('green_api_token')->nullable();
            $table->string('green_webhook_url')->nullable();
            $table->integer('green_sent_message')->default(0);
            $table->integer('green_received_message')->default(0);
            $table->integer('green_monthly_limit')->default(0);
            $table->boolean('green_active')->default(0);
            $table->integer('erp_system')->nullable()->comment('1 = SAP Business One, 2 = Oracle NetSuite, 3  = QuickBooks, 4 = Xero, 5 = Sage, 6 = Sage Intacct, 7 = Sage One, 8 = Sage One Cloud');
            $table->string('erp_api_base_url')->nullable();
            $table->string('erp_api_token')->nullable();
            $table->string('erp_api_secret')->nullable();
            $table->integer('erp_auto_sync')->default(6);
            $table->boolean('erp_active')->default(0);
            $table->boolean('status')->default(1);
            $table->integer('login_user_id')->nullable();
            $table->integer('createdby_id')->nullable();
            $table->integer('updatedby_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('companies');
    }
};
