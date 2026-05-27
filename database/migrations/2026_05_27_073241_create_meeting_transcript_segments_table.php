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
        Schema::create('meeting_transcript_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->cascadeOnDelete();
            $table->string('speaker_name')->nullable()->index();
            $table->decimal('speaker_confidence', 5, 2)->nullable();
            $table->decimal('start_second', 10, 2)->nullable();
            $table->decimal('end_second', 10, 2)->nullable();
            $table->text('text');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_transcript_segments');
    }
};
