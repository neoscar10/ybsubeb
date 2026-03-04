<?php

namespace Database\Seeders;

use App\Models\SubebClass;
use Illuminate\Database\Seeder;

class SubebClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            // ECE
            ['name' => 'Nursery 1', 'level_group' => 'ece', 'sort_order' => 1],
            ['name' => 'Nursery 2', 'level_group' => 'ece', 'sort_order' => 2],
            // Primary
            ['name' => 'Primary 1', 'level_group' => 'primary', 'sort_order' => 3],
            ['name' => 'Primary 2', 'level_group' => 'primary', 'sort_order' => 4],
            ['name' => 'Primary 3', 'level_group' => 'primary', 'sort_order' => 5],
            ['name' => 'Primary 4', 'level_group' => 'primary', 'sort_order' => 6],
            ['name' => 'Primary 5', 'level_group' => 'primary', 'sort_order' => 7],
            ['name' => 'Primary 6', 'level_group' => 'primary', 'sort_order' => 8],
            // JSS
            ['name' => 'JSS 1', 'level_group' => 'junior_secondary', 'sort_order' => 9],
            ['name' => 'JSS 2', 'level_group' => 'junior_secondary', 'sort_order' => 10],
            ['name' => 'JSS 3', 'level_group' => 'junior_secondary', 'sort_order' => 11],
        ];

        foreach ($classes as $class) {
            SubebClass::updateOrCreate(['name' => $class['name']], $class);
        }
    }
}
