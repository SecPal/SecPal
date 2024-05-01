<?php

use App\Models\Category;
use App\Models\Journal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_journal', function (Blueprint $table) {
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Journal::class);

            $table->boolean('rescue_involved')->default(0);
            $table->boolean('fire_involved')->default(0);
            $table->boolean('police_involved')->default(0);

            $table->timestamps();

            $table->primary(['category_id', 'journal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_journal');
    }
};
