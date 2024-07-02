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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('eventDate');
            $table->time('eventTime');
            $table->decimal('eventPrice', 8, 2); // Change precision and scale as per your requirement
            $table->string('image')->nullable();
            $table->string('eventAddress');
            $table->string('charityAddress');
            $table->boolean('isActive')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
