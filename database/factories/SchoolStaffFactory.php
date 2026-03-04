<?php

namespace Database\Factories;

use App\Models\SchoolStaff;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolStaffFactory extends Factory
{
    protected $model = SchoolStaff::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);

        return [
            'staff_type' => $this->faker->randomElement(['teaching_staff', 'non_teaching_staff']), // Assuming these are the enum values
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName,
            'other_name' => $this->faker->firstName($gender),
            'gender' => $gender,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'date_of_birth' => $this->faker->dateTimeBetween('-50 years', '-22 years'),
            'address' => $this->faker->address,
            'qualification' => $this->faker->randomElement(['NCE', 'B.Ed', 'B.Sc', 'HND', 'M.Ed']),
            'designation' => $this->faker->jobTitle,
            'is_active' => true,
            'can_upload_students' => false,
            // school_id will be overridden
        ];
    }
}
