<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\LoanRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_processing_a_loan_updates_stock_only_on_handover_and_return(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $equipment = Equipment::create([
            'name' => 'Multimeter', 'category' => 'Tanpa kategori',
            'condition' => 'Baik', 'available_quantity' => 4,
        ]);
        $this->actingAs($student)->post(route('mahasiswa.requests.store'), [
            'equipment_id' => $equipment->id, 'quantity' => 2,
            'borrow_date' => today()->toDateString(),
            'return_date' => today()->addWeek()->toDateString(),
        ])->assertRedirect(route('mahasiswa.requests'));

        $loan = LoanRequest::sole();
        $process = route('petugas.requests.process', $loan);
        $this->patch($process, ['action' => 'approve'])->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get(route('petugas.requests'))->assertOk()->assertSee('Multimeter');
        $this->patch($process, ['action' => 'approve'])->assertRedirect(route('petugas.requests'));
        $this->assertSame(4, (int) $equipment->fresh()->available_quantity);
        $this->patch($process, ['action' => 'handover'])->assertRedirect(route('petugas.requests'));
        $this->patch($process, ['action' => 'handover'])->assertSessionHasErrors('action');
        $this->assertSame(2, (int) $equipment->fresh()->available_quantity);
        $this->delete(route('petugas.equipment.destroy', $equipment))->assertSessionHasErrors('equipment');

        $this->patch($process, ['action' => 'return', 'condition' => 'Buruk'])->assertRedirect(route('petugas.requests'));
        $this->patch($process, ['action' => 'return', 'condition' => 'Buruk'])->assertSessionHasErrors('action');
        $this->assertSame(4, (int) $equipment->fresh()->available_quantity);
        $this->assertSame('Buruk', $equipment->fresh()->condition);
        $this->assertSame('selesai', $loan->fresh()->status);
        $this->get(route('petugas.history'))->assertOk()->assertSee('Dikembalikan')->assertSee('Multimeter');
        $this->actingAs($student)->get(route('mahasiswa.requests'))->assertOk()->assertSee('Selesai');
    }
}
