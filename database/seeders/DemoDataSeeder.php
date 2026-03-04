<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Student;
use App\Models\SchoolStaff;
use App\Models\SubebClass;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Classes Exist
        if (SubebClass::count() === 0) {
            $this->call(SubebClassSeeder::class);
        }
        $classIds = SubebClass::orderBy('sort_order')->pluck('id')->toArray();

        if (empty($classIds)) {
            $this->command->error("No classes found. Seed SubebClasses first.");
            return;
        }

        // 2. Create Schools
        $this->command->info('Creating schools...');
        $schools = School::factory()->count(25)->create();

        foreach ($schools as $school) {
            $this->command->info("Seeding data for {$school->name}...");

            // 3. Create Staff (8-20 per school)
            SchoolStaff::factory()
                ->count(rand(8, 20))
                ->create([
                    'school_id' => $school->id,
                ]);

            // 4. Create Students (50-100 per school)
            $studentCount = rand(50, 100);
            $students = Student::factory()
                ->count($studentCount)
                ->make()
                ->each(function ($student, $index) use ($school, $classIds) {
                    $student->school_id = $school->id;
                    $student->class_id = $classIds[array_rand($classIds)];
                    
                    // Simple admission number generation
                    $student->admission_no = $school->code . '/' . date('Y') . '/' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
                    
                    $student->save();
                });
            
            // Update School Stats if columns exist (optional based on your schema)
            // But we specifically need to NOT assume too much. 
            // However, the School model has 'total_students' and 'total_teachers' in fillable and we saw them in the file.
            $school->update([
                'total_students' => $studentCount,
                'total_teachers' => SchoolStaff::where('school_id', $school->id)->where('staff_type', 'teaching_staff')->count(),
                'students_male' => Student::where('school_id', $school->id)->where('gender', 'male')->count(),
                'students_female' => Student::where('school_id', $school->id)->where('gender', 'female')->count(),
                'teachers_male' => SchoolStaff::where('school_id', $school->id)->where('staff_type', 'teaching_staff')->where('gender', 'male')->count(),
                'teachers_female' => SchoolStaff::where('school_id', $school->id)->where('staff_type', 'teaching_staff')->where('gender', 'female')->count(),
            ]);
        }

        $this->command->info('Demo data seeding completed!');
    }
}
