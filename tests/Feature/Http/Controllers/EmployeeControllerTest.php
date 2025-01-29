<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Employee::findOrFail($id)>update($request>validated());;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\EmployeeController
 */
final class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function methods_behaves_as_expected(): void
    {
        $response = $this->get(route('employees.methods'));

        $employee->refresh();

        $response->assertSessionHas('Employee::create($request->validated());', $Employee::create($request->validated()););
    }
}
