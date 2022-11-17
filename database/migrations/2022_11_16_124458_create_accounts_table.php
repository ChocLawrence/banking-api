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
        Schema::dropIfExists('accounts');
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_number')->unique();
            $table->decimal('balance', 12, 2);
            $table->string('pin')->nullable(); 
            $table->boolean('transfer_without_pin')->default(true); 
            $table->foreignId('account_type')
            ->constrained('accounttypes')
            ->onDelete('cascade');
            $table->foreignId('user_id')
                 ->constrained('users')
                 ->onDelete('cascade');  
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
        Schema::dropIfExists('accounts');
        Schema::enableForeignKeyConstraints();
    }
};
