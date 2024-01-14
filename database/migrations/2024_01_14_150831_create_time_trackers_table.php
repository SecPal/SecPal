<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTrackersTable extends Migration
{
    public function up(): void
    {
        Schema::create('time_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Location::class)->constrained();
            $table->string('event');
            $table->dateTime('real_time');
            $table->dateTime('plan_time');
            $table->tinyText('comment')->nullable();
            $table->foreignId('entry_by')->nullable()->constrained()->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_trackers');
    }
}
