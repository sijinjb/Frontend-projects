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
        Schema::table('invoices_master', function (Blueprint $table) {
            $table->unsignedBigInteger('manager_id')->nullable()->after('due_date');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('your_table_name', function (Blueprint $table) {
            // Drop the column when rolling back the migration
            $table->dropColumn('manager_id');
            $table->dropForeign(['manager_id']);
        });
    }
};
