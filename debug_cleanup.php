<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Starting cleanup...\n";

// 1. Clean Users table
if (Schema::hasColumn('users', 'school_id')) {
    echo "Column school_id found in users. Dropping...\n";
    try {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });
        echo "Dropped FK.\n";
    } catch (\Exception $e) {
        echo "FK Drop failed (might not exist): " . $e->getMessage() . "\n";
    }
    
    try {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn('school_id');
        });
        echo "Dropped column.\n";
    } catch (\Exception $e) {
        echo "Col Drop failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "Column school_id NOT found in users.\n";
}

// 2. Drop tables
Schema::disableForeignKeyConstraints();
Schema::dropIfExists('school_data_updates');
Schema::dropIfExists('schools');
Schema::enableForeignKeyConstraints();
echo "Dropped tables.\n";

// 3. Clear Migrations Table
DB::table('migrations')->where('migration', 'like', '%create_schools_table%')->delete();
DB::table('migrations')->where('migration', 'like', '%create_school_data_updates_table%')->delete();
DB::table('migrations')->where('migration', 'like', '%add_school_id_to_users_table%')->delete();
echo "Cleared migration entries.\n";

echo "Cleanup done.\n";
