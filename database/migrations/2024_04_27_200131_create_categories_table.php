<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('location_id')->nullable();
            $table->string('name');
            $table->boolean('rescue_possible')->default(0);
            $table->boolean('fire_possible')->default(0);
            $table->boolean('police_possible')->default(0);
            $table->unsignedSmallInteger('usually_involved')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
