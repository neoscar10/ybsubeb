<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to create table...\n";
    Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS test_script");
    Illuminate\Support\Facades\DB::statement("CREATE TABLE test_script (id INT)");
    echo "SUCCESS: Table created.\n";
    Illuminate\Support\Facades\DB::statement("DROP TABLE IF EXISTS test_script");
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
