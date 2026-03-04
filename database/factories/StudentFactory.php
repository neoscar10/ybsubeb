<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        
        return [
            'first_name' => $this->faker->firstName($gender),
            'last_name' => $this->faker->lastName,
            'other_name' => $this->faker->firstName($gender), // simple random
            'gender' => $gender,
            'date_of_birth' => $this->faker->dateTimeBetween('-15 years', '-5 years'),
            'guardian_name' => $this->faker->name,
            'guardian_phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'enrollment_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'inactive']), // Weighted active
            // school_id and class_id will be overridden
        ];
    }
}
