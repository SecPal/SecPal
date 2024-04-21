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
            $table->text('text');
            $table->text('actions')->nullable();
            $table->tinyInteger('involved');
            $table->foreignIdFor(User::class, 'reported_by');
            $table->foreignIdFor(User::class, 'entry_by');
            $table->boolean('review_required');
            $table->foreignIdFor(User::class, 'reviewed_by')->nullable();
            $table->tinyText('reference')->nullable();
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
