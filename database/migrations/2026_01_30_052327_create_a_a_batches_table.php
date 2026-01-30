<?php

use App\Models\User;
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
        Schema::create('a_a_batches', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('description')->nullable();
            $table->string('status');
            $table->foreignIdFor(User::class)->nullable();
            $table->boolean('is_processed')->default(false);
            $table->string('reply_to_address')->nullable();
            $table->string('reply_to_name')->nullable();
            $table->string('acct_mgr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_a_batches');
    }
};
