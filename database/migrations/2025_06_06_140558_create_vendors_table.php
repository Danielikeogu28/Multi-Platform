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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->unique();
            $table->string('business_address');
            $table->string('home_address');
            $table->string('email')->unique();
            $table->string('street');
            $table->string('state');
            $table->string('city');
            $table->string('country');
            $table->string('phone');
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_active')->default(true);

            $table->foreignId('vendor_category_id')->constrained('vendor_categories')->onDelete('cascade');

            $table->string('role')->default('vendor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
