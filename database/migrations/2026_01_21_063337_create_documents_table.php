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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('document_categories')->cascadeOnDelete(); // ← Better syntax
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete(); // ← Better syntax
            $table->string('current_version', 20)->default('1.0');
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type', 100);
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete(); // ← Better syntax
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for common queries
            $table->index(['category_id', 'created_at']); // ← Add this
            $table->index(['division_id', 'created_at']); // ← Add this
            $table->index('uploaded_by'); // ← Add this

            // $table->fullText(['title', 'description']); //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
