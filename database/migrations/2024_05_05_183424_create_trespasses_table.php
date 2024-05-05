<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trespasses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id');
            $table->foreignId('participant_id');
            $table->foreignIdFor(User::class, 'charge_filed_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trespasses');
    }
};
