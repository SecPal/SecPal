<?php

/**
 * Copyright (c) 2024 Holger Schmermbeck. Licensed under the EUPL-1.2 or later.
 */

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationUserTable extends Migration
{
    public function up(): void
    {
        Schema::create('location_user', function (Blueprint $table) {
            $table->foreignIdFor(Location::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->timestamps();

            $table->primary(['location_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_user');
    }
}
