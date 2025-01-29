<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\LeaveRequest::findOrFail($id)>update($request>validated());;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LeaveRequestController
 */
final class LeaveRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function methods_behaves_as_expected(): void
    {
        $response = $this->get(route('leave-requests.methods'));

        $leaveRequest->refresh();

        $response->assertSessionHas('LeaveRequest::create($request->validated());', $LeaveRequest::create($request->validated()););
    }
}
