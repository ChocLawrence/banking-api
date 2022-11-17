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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('transactions');
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sender_account_number');
            $table->foreignId('sender_account')
            ->constrained('accounts')
            ->onDelete('cascade');
            $table->string('receiver_account_number');
            $table->foreignId('receiver_account')
            ->constrained('accounts')
            ->onDelete('cascade');  
            $table->enum('status',['pending','successful','failed','reversed']);
            $table->string('currency')->default('XAF');
            $table->decimal('amount', 11, 2);
            $table->foreignId('user_id')
                 ->constrained('users')
                 ->onDelete('cascade');      
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('transactions');
        Schema::enableForeignKeyConstraints();
    }
};
