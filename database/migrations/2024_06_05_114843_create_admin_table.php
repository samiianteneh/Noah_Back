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
    Schema::create('admin', function (Blueprint $table) {
        $table->id();
        $table->string('fullName');
        $table->string('email')->unique();
        $table->string('phone');
        $table->string('password');
        $table->string('image')->nullable(); // If you want the image field to be optional
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
