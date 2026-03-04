<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "Starting manual migration...\n";

try {
    // 1. Assessment Windows
    if (!Schema::hasTable('assessment_windows')) {
        echo "Creating assessment_windows...\n";
        Schema::create('assessment_windows', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('scope')->nullable()->comment('statewide, lga, etc.');
            $table->dateTime('opens_at');
            $table->dateTime('closes_at');
            $table->string('status')->default('draft');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->index(); // Using index for safety
            $table->timestamps();
        });
        echo " - assessment_windows created.\n";
    } else {
        echo " - assessment_windows already exists.\n";
    }

    // 2. Needs Assessments
    if (!Schema::hasTable('needs_assessments')) {
        echo "Creating needs_assessments...\n";
        Schema::create('needs_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_window_id')->constrained('assessment_windows')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('submitted_by')->nullable()->index();
            $table->dateTime('submitted_at')->nullable();
            $table->string('status')->default('draft');
            $table->text('principal_comment')->nullable();
            $table->text('admin_comment')->nullable();
            $table->timestamps();

            $table->unique(['assessment_window_id', 'school_id']);
        });
        echo " - needs_assessments created.\n";
    } else {
        echo " - needs_assessments already exists.\n";
    }

    // 3. Needs Items
    if (!Schema::hasTable('needs_items')) {
        echo "Creating needs_items...\n";
        Schema::create('needs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('needs_assessment_id')->constrained('needs_assessments')->cascadeOnDelete();
            $table->string('category'); // removed comment() to be safe
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable();
            $table->string('priority');
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->text('justification')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('decision_by')->nullable()->index();
            $table->dateTime('decided_at')->nullable();
            $table->text('decision_note')->nullable();
            $table->timestamps();
        });
        echo " - needs_items created.\n";
    } else {
        echo " - needs_items already exists.\n";
    }

    // 4. Needs Attachments
    if (!Schema::hasTable('needs_attachments')) {
        echo "Creating needs_attachments...\n";
        Schema::create('needs_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('needs_item_id')->nullable()->constrained('needs_items')->onDelete('set null');
            $table->foreignId('needs_assessment_id')->constrained('needs_assessments')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime');
            $table->integer('size');
            $table->foreignId('uploaded_by')->index();
            $table->timestamps();
        });
        echo " - needs_attachments created.\n";
    } else {
        echo " - needs_attachments already exists.\n";
    }

    echo "MIGRATION COMPLETE.\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
