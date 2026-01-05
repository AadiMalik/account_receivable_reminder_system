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
        Schema::create('invoice_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('customer_invoice_id');
            $table->integer('customer_id')->nullable();
            $table->enum('reminder_type', [
                'before_due',
                'on_due',
                'after_due_1',
                'after_due_2'
            ]);
            $table->string('customer_phone')->nullable();
            $table->boolean('whatsapp_exists')->default(false);
            $table->boolean('message_sent')->default(false);
            $table->string('whatsapp_message_id')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
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
        Schema::dropIfExists('invoice_reminder_logs');
    }
};
