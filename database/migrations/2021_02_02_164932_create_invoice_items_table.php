<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'invoice_items', function (Blueprint $table){
            $table->id();
            $table->integer('invoice_id');
            $table->integer('item');
            $table->integer('quantity');
            $table->string('tax')->nullable();
            $table->float('discount')->default('0.00');
            $table->float('price')->default('0.00');
            $table->text('description')->nullable();
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
