<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('name')->index();
            $table->string('type')->default('Customer'); //Allowed Customer,Supplier
            $table->string('mobile');
            $table->string('gstno');
            $table->string('pan')->nullable();
            $table->text('billing_address');
            $table->string('billing_state');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->unique(['mobile', 'gstno'], 'mob_gst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
