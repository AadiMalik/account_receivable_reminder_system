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
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('before_due')->default(0);
            $table->boolean('on_due')->default(1);
            $table->integer('after_due_1')->default(0);
            $table->integer('after_due_2')->default(0);
            $table->integer('max_reminders')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('before_due');
            $table->dropColumn('on_due');
            $table->dropColumn('after_due_1');
            $table->dropColumn('after_due_2');
            $table->dropColumn('max_reminders');
        });
    }
};
