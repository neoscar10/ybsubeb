<?php
try {
    \Illuminate\Support\Facades\DB::statement("ALTER TABLE schools ADD COLUMN total_classes INT UNSIGNED DEFAULT 0 AFTER total_classrooms");
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
