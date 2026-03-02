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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 50)->unique();
            $table->string('phone', 12);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        });

        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        });

        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description');
            $table->decimal('daily_price', 10, 2);

            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
        });

        Schema::create('equipment_sport', function (Blueprint $table) {
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->foreignId('sport_id')->constrained('sports')->cascadeOnDelete();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->primary(['equipment_id', 'sport_id']);
        });

        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->date('startDate');
            $table->date('endDate');
            $table->decimal('total_price', 10, 2);

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->text('comment');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('rental_id')->constrained('rentals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('sports');
        Schema::dropIfExists('rentals');
        Schema::dropIfExists('equipment_sport');
        Schema::dropIfExists('rentals');
        Schema::dropIfExists('reviews');
    }
};

