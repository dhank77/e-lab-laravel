<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentLoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_apply_and_cancel_without_changing_stock(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $equipment = Equipment::create([
            'name' => 'Multimeter', 'category' => 'Tanpa kategori',
            'condition' => 'Baik', 'available_quantity' => 2,
        ]);
        $application = [
            'equipment_id' => $equipment->id, 'quantity' => 2,
            'borrow_date' => today()->toDateString(),
            'return_date' => today()->addDays(7)->toDateString(),
        ];

        $this->actingAs($student)->get(route('mahasiswa.dashboard'))->assertOk()->assertSee('Multimeter');
        $this->post(route('mahasiswa.requests.store'), array_replace($application, ['quantity' => 3]))
            ->assertSessionHasErrors('quantity');
        $this->post(route('mahasiswa.requests.store'), $application)->assertRedirect(route('mahasiswa.requests'));
        $loan = LoanRequest::sole();
        $this->assertEquals($student->id, $loan->user_id);
        $this->assertSame('menunggu', $loan->status);
        $this->get(route('mahasiswa.requests'))->assertOk()->assertSee('Multimeter');

        $cancel = route('mahasiswa.requests.cancel', $loan);
        $this->actingAs(User::factory()->create(['role' => 'mahasiswa']))->patch($cancel)->assertNotFound();
        $this->actingAs($student)->patch($cancel)->assertRedirect(route('mahasiswa.requests'));
        $this->assertSame('dibatalkan', $loan->fresh()->status);
        $this->assertEquals(2, $equipment->fresh()->available_quantity);
    }
}
