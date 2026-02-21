<?php

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
        Schema::create('punchlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setup_id')->constrained('setups')->cascadeOnDelete();
            $table->text('text');
            $table->string('media_type')->nullable();
            $table->string('media_url')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('laughs')->default(0);
            $table->timestamps();

            $table->index(['setup_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punchlines');
    }
};
