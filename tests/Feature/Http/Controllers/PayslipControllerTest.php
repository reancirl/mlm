<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Payslip::findOrFail($id)>update($request>validated());;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PayslipController
 */
final class PayslipControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function methods_behaves_as_expected(): void
    {
        $response = $this->get(route('payslips.methods'));

        $payslip->refresh();

        $response->assertSessionHas('Payslip::create($request->validated());', $Payslip::create($request->validated()););
    }
}
