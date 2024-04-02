<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'items', function (Blueprint $table){
            $table->id();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->float('sale_price')->default(0.0);
            $table->float('purchase_price')->default(0.0);
            $table->string('tax')->default(0);
            $table->string('category')->default(0);
            $table->string('unit')->default(0);
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('items');
    }
}
