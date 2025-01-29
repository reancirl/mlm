<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Instance;
use App\Models\User;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'middle_namename' => $this->faker->word(),
            'last_name' => $this->faker->lastName(),
            'position' => $this->faker->word(),
            'contact_info' => $this->faker->word(),
            'employment_type' => $this->faker->randomElement(["full-time","part-time","contract"]),
            'start_date' => $this->faker->date(),
            'salary' => $this->faker->randomFloat(2, 0, 999999.99),
            'sss_number' => $this->faker->word(),
            'pagibig_number' => $this->faker->word(),
            'philhealth_number' => $this->faker->word(),
            'instance_id' => Instance::factory(),
            'user_id' => User::factory(),
        ];
    }
}
