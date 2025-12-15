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
        Schema::create('skill_sessions', function (Blueprint $table) {
            $table->id();

            // Enseignant (celui qui enseigne)
            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Apprenant (celui qui apprend)
            $table->foreignId('learner_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Compétence enseignée
            $table->foreignId('skill_id')
                  ->constrained('skills')
                  ->onDelete('cascade');

            // Détails de la session
            $table->dateTime('scheduled_at'); // Date et heure prévues
            $table->enum('status', ['en_attente', 'confirmee', 'terminee', 'annulee'])
                  ->default('en_attente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_sessions');
    }
};
