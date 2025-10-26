<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_user_borrowed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->dateTime('borrowed_date');
            $table->dateTime('due_date');
            $table->dateTime('returned_date')->nullable();
            $table->timestamps();

            // Prevent duplicate borrows
            $table->unique(['user_id', 'book_id', 'borrowed_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_user_borrowed');
    }
};