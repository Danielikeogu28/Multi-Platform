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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Leather Jacket"
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->enum('condition', ['new', 'old']); // Old and new
            $table->enum('status', ['active', 'pending', 'sold', 'rejected'])->default('active');
            $table->string('image')->nullable();

            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_category_id')->constrained('product_categories')->onDelete('cascade');
            $table->foreignId('vendor_category_id')->constrained()->onDelete('cascade');

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
