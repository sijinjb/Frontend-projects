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
        Schema::create('invoices_master', function (Blueprint $table) {
            $table->id();
            $table->uuid()->index();
            $table->date('generated_date')->index();
            $table->unsignedBigInteger('client');
            $table->float('total_amount' , 10 , 2, true)->nullable();
            $table->float('total_gst' , 10 , 2, true)->nullable();
            $table->float('payable' , 10 , 2, true)->nullable();
            $table->string('type')->default('Payment');// Allowed Delivery,Payment
            $table->string('status')->default('Pending');// Allowed Draft,Pending,Collected,Rejected,Discarded
            $table->string('comments')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->date('due_date');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('client')->references('id')->on('clients')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_master');
    }
};
