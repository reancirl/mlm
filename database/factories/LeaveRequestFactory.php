<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Instance;
use App\Models\LeaveRequest;

class LeaveRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LeaveRequest::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'leave_type' => $this->faker->randomElement(["vacation","sick","emergency","others"]),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(["pending","approved","rejected"]),
            'reason' => $this->faker->text(),
            'employee_id' => Employee::factory(),
            'instance_id' => Instance::factory(),
        ];
    }
}
