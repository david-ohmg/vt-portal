<?php

use App\Models\ScriptBatch;
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
        Schema::create('script_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ScriptBatch::class)->constrained();
            $table->string('name');
            $table->string('notes')->nullable();
            $table->text('text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('script_items');
    }
};
