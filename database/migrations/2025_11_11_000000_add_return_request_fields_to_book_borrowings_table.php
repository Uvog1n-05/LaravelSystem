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
        Schema::table('book_borrowings', function (Blueprint $table) {
            $table->boolean('return_requested')->default(false)->after('extensions_count');
            $table->timestamp('return_requested_at')->nullable()->after('return_requested');
            $table->unsignedBigInteger('return_verified_by')->nullable()->after('return_requested_at');
            $table->timestamp('return_verified_at')->nullable()->after('return_verified_by');

            $table->foreign('return_verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_borrowings', function (Blueprint $table) {
            $table->dropForeign(['return_verified_by']);
            $table->dropColumn(['return_verified_at', 'return_verified_by', 'return_requested_at', 'return_requested']);
        });
    }
};
