<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Payslip;
use App\Models\Instance;

class PayslipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payslip::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'basic_salary' => $this->faker->randomFloat(2, 0, 999999.99),
            'deductions' => $this->faker->randomFloat(2, 0, 999999.99),
            'sss_contribution' => $this->faker->randomFloat(2, 0, 999999.99),
            'pagibig_contribution' => $this->faker->randomFloat(2, 0, 999999.99),
            'philhealth_contribution' => $this->faker->randomFloat(2, 0, 999999.99),
            'overtime' => $this->faker->randomFloat(2, 0, 999999.99),
            'net_pay' => $this->faker->randomFloat(2, 0, 999999.99),
            'period_start' => $this->faker->date(),
            'period_end' => $this->faker->date(),
            'generated_at' => $this->faker->dateTime(),
            'employee_id' => Employee::factory(),
            'instance_id' => Instance::factory(),
        ];
    }
}
