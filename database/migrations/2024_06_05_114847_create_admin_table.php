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
    Schema::create('admins', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('fullName');
        $table->string('email')->unique();
        $table->string('phone');
        $table->string('role');
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
