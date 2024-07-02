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
         Schema::create('volunteers', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Auto-incrementing ID column
        $table->string('name'); // Name column
        $table->text('description'); // Description column
        $table->timestamps(); // Created at and Updated at timestamps
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
