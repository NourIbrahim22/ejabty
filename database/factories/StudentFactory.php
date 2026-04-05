<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = \App\Models\Student::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['grade9', 'grade12_sci', 'grade12_arts'];

        return [
            'firstName'      => $this->faker->firstName(),
            'fatherName'     => $this->faker->firstNameMale(), // assuming fatherName is male first name
            'lastName'       => $this->faker->lastName(),
            'phoneNumber'    => $this->faker->unique()->phoneNumber(),
            'password'       => bcrypt('password123'), // default password
            'studentPackage' => $this->faker->randomElement($types), // type of student
            'studentHealth'  => $this->faker->sentence(5), // some health info
            'examHall'       => null, // will assign later with your algorithm
        ];
    }
}
