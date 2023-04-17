<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('product_name')->nullable();
        $table->bigInteger('category_id')->unsigned();
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        $table->bigInteger('subcategory_id')->unsigned();
        $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('cascade');
        $table->string('price')->nullable();
        $table->string('stock')->nullable();
        $table->enum('d_per',[0])->default(0);
        $table->enum('d_price',[0])->default(0);
        $table->text('description')->nullable();
        $table->enum('status',[0,1])->default(0);
        $table->date('start_date')->nullable();
        $table->date('expiry_date')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
