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
        Schema::create('requisition_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('requisition_id')->references('id')->on('requisitions')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('product_id')->references('id')->on('products')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('total_requisition',15,2);
            $table->decimal('total_received',15,2);
            $table->string('status')->default('pending');
            $table->string('remarks')->nullable();
            $table->string('where_to_use')->nullable();
            $table->string('store_code')->nullable();
            $table->string('size')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_products');
    }
};
