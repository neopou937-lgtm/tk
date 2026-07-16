<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nipd' => fake()->unique()->numerify('NIPD-####'),
            'nisn' => fake()->unique()->numerify('##########'),
            'student_nik' => fake()->unique()->numerify('################'),
            'level' => 'PAUD',
            'class_name' => fake()->randomElement(['A', 'B']),
            'gender' => fake()->randomElement(['laki-laki', 'perempuan']),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date(),
            'religion' => 'Islam',
            'address' => fake()->address(),
            'child_order' => fake()->numberBetween(1, 5),
            'father_name' => fake()->name('male'),
            'father_nik' => fake()->numerify('################'),
            'father_phone' => fake()->phoneNumber(),
            'mother_name' => fake()->name('female'),
            'mother_nik' => fake()->numerify('################'),
            'mother_phone' => fake()->phoneNumber(),
            'status' => 'aktif',
        ];
    }
}
