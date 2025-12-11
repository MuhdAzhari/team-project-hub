<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Polymorphic relation: Project / Task
            $table->morphs('subject'); // subject_type, subject_id

            $table->string('action'); // e.g. project_created, task_updated, task_status_changed
            $table->text('description')->nullable();

            // Store important changes (status, assigned_to, etc.) as JSON
            $table->json('changes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
