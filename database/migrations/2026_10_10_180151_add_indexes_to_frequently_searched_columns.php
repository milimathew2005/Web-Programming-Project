<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->index('title');
            $table->index('author');
        });

        Schema::table('book_copies', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['due_date']);
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('book_copies', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['title']);
            $table->dropIndex(['author']);
        });
    }
};
