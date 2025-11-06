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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rfc')->unique();
            $table->string('legal_name');
            $table->text('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('contact_person');
            $table->string('category')->nullable();
            $table->json('business_hours')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('ratings_enabled')->default(true);
            $table->foreignId('package_id')->nullable()->constrained();
            $table->timestamp('package_expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
