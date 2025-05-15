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
        Schema::create('survivors', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('fema_id')->nullable(); // FEMA-ID
            $table->string('name')->nullable(); // Name
            $table->string('address')->nullable(); // Address
            $table->string('phone')->nullable(); // Phone
            $table->integer('hh_size')->nullable(); // HH Size
            $table->date('li_date')->nullable(); // LI Date
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survivors');
    }
};
