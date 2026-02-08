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
            $table->string('document_number')->nullable();

            $table->foreignId('category_id')->constrained('document_categories');
            $table->foreignId('division_id')->nullable()->constrained('divisions');
            $table->foreignId('uploaded_by')->constrained('users');

            $table->string('file_path');
            $table->string('current_version')->default('1.0');

            $table->timestamps();
            $table->softDeletes();
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
