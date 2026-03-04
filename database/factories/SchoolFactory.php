<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        $lgas = [
            'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba', 
            'Gulani', 'Jakusko', 'Karasuwa', 'Machina', 'Nangere', 'Nguru', 
            'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'
        ];

        return [
            'code' => 'SCH-' . strtoupper($this->faker->unique()->bothify('###??')),
            'name' => 'GSS ' . $this->faker->city . ' ' . $this->faker->suffix,
            'school_type' => $this->faker->randomElement(['primary', 'junior_secondary', 'basic', 'special']),
            'ownership' => 'Government',
            'status' => 'active',
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'year_established' => $this->faker->year,
            'address' => $this->faker->address,
            'lga' => $this->faker->randomElement($lgas),
            'ward' => $this->faker->word,
            'total_students' => 0, // Will be updated by seeder
            'total_teachers' => 0, // Will be updated by seeder
            'has_water' => $this->faker->boolean,
            'has_toilets' => $this->faker->boolean,
            'has_electricity' => $this->faker->boolean,
        ];
    }
}
