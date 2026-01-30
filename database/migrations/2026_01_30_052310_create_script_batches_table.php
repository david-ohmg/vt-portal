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
        Schema::create('script_batches', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('notes')->nullable();
            $table->string('priority')->default('normal');
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
        Schema::dropIfExists('script_batches');
    }
};
