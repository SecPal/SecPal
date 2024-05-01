<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id');
            $table->foreignId('category_id');
            $table->text('description');
            $table->text('measures')->nullable();
            $table->tinyText('area');
            $table->unsignedSmallInteger('involved');
            $table->boolean('rescue_involved');
            $table->boolean('fire_involved');
            $table->boolean('police_involved');
            $table->foreignIdFor(User::class, 'reported_by');
            $table->foreignIdFor(User::class, 'entry_by');
            $table->foreignIdFor(User::class, 'reviewed_by')->nullable();
            $table->dateTime('incident_time');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
