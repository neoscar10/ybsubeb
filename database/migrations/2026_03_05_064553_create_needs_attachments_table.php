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
        Schema::create('needs_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('needs_item_id')->nullable()->constrained('needs_items')->cascadeOnDelete();
            $table->foreignId('needs_assessment_id')->nullable()->constrained('needs_assessments')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime');
            $table->unsignedBigInteger('size');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('needs_attachments');
    }
};
