<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->unsignedBigInteger('sub_category_id');
            $table->string('title', 200)->nullable();
            $table->string('color', 200)->nullable();
            $table->string('price', 50);
            $table->string('discount', 50)->nullable();
            $table->string('offer', 50)->nullable();
            $table->text('des');
            $table->string('image', 255)->nullable();

            $table->foreign('sub_category_id')->references('id')->on('sub_categories')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
