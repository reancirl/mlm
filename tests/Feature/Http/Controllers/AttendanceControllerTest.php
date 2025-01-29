<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Attendance::findOrFail($id)>update($request>validated());;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AttendanceController
 */
final class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function methods_behaves_as_expected(): void
    {
        $response = $this->get(route('attendances.methods'));

        $attendance->refresh();

        $response->assertSessionHas('Attendance::create($request->validated());', $Attendance::create($request->validated()););
    }
}
