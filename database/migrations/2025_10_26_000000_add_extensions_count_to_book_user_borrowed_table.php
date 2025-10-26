<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_user_borrowed', function (Blueprint $table) {
            $table->integer('extensions_count')->default(0)->after('returned_date');
        });
    }

    public function down(): void
    {
        Schema::table('book_user_borrowed', function (Blueprint $table) {
            $table->dropColumn('extensions_count');
        });
    }
};