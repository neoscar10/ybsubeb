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
        Schema::create('needs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('needs_assessment_id')->constrained('needs_assessments')->cascadeOnDelete();
            $table->string('category');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable();
            $table->string('priority');
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->text('justification')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('decided_at')->nullable();
            $table->foreignId('decision_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('needs_items');
    }
};
