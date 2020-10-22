<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('borrows', function (Blueprint $table) {
            $table->unsignedBigInteger('borr_id')->autoIncrement();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('borr_status')->default(0);
            $table->dateTime('borrow_date');
            $table->dateTime('return_date');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('book_id')->references('book_id')->on('books');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('borrows');
        Schema::enableForeignKeyConstraints();
    }
}
