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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('name');
            $table->string('school_type'); // primary, junior_secondary, basic, special
            $table->string('ownership')->default('public');
            $table->string('status')->default('active'); // active, inactive
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->year('year_established')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Location
            $table->string('lga');
            $table->string('ward')->nullable();
            $table->string('community')->nullable();
            $table->text('address')->nullable();

            // Core Stats
            $table->integer('total_students')->default(0);
            $table->integer('total_teachers')->default(0);
            $table->integer('total_classrooms')->default(0);

            // Disaggregated stats
            $table->integer('students_male')->default(0);
            $table->integer('students_female')->default(0);
            $table->integer('teachers_male')->default(0);
            $table->integer('teachers_female')->default(0);

            // Infrastructure snapshot
            $table->boolean('has_water')->default(false);
            $table->boolean('has_toilets')->default(false);
            $table->boolean('has_electricity')->default(false);
            $table->text('notes')->nullable();

            // Audit
            $table->foreignId('last_updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
