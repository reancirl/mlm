<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Attendance;
use App\Models\Employee;

class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'time_in' => $this->faker->time(),
            'time_out' => $this->faker->time(),
            'status' => $this->faker->randomElement(["present","absent","leave","half-day"]),
            'employee_id' => Employee::factory(),
        ];
    }
}
