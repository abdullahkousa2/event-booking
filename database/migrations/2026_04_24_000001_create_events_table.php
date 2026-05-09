<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location');
            $table->dateTime('event_date');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->unsignedInteger('total_seats');
            $table->unsignedInteger('available_seats');
            $table->enum('status', ['active', 'cancelled', 'completed'])->default('active');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('event_date');
            $table->index('status');
            $table->index('available_seats');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
