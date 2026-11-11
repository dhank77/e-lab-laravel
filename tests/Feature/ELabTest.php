<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ELabTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_guest_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('E-Lab');
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_register_and_login(): void
    {
        $this->post('/register', [
            'name' => 'Budi', 'nim' => 'D1', 'email' => 'budi@test.id',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
            'role' => 'admin', // harus diabaikan
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertSame('user', User::where('email', 'budi@test.id')->first()->role);

        $this->post('/logout');
        $this->post('/login', ['email' => 'budi@test.id', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => 'budi@test.id', 'password' => 'rahasia123'])->assertRedirect('/dashboard');
    }

    public function test_full_loan_flow(): void
    {
        $user = User::where('email', 'mahasiswa@elab.test')->first();
        $admin = User::where('email', 'admin@elab.test')->first();
        $item = Item::where('name', 'Osiloskop Rigol')->first(); // stok 2

        foreach (['/dashboard', '/labs', '/labs/'.$item->lab_id, '/loans', "/items/{$item->id}/borrow"] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
        $this->actingAs($user)->get('/admin/loans')->assertForbidden();

        $payload = ['quantity' => 3, 'loan_date' => now()->toDateString(), 'due_date' => now()->addDay()->toDateString(), 'purpose' => 'Praktikum'];
        $this->actingAs($user)->post("/items/{$item->id}/borrow", $payload)->assertSessionHasErrors('quantity');
        $this->actingAs($user)->post("/items/{$item->id}/borrow", ['quantity' => 2] + $payload)->assertRedirect('/loans');

        $loan = Loan::where('item_id', $item->id)->first();
        $this->assertSame('pending', $loan->status);

        foreach (['/dashboard', '/admin/loans', '/admin/items', '/admin/labs/create', "/admin/items/{$item->id}/edit"] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        $this->actingAs($admin)->patch("/admin/loans/{$loan->id}", ['action' => 'approve']);
        $this->assertSame('approved', $loan->fresh()->status);
        $this->assertSame(0, $item->fresh()->availableStock());

        $this->actingAs($admin)->patch("/admin/loans/{$loan->id}", ['action' => 'return', 'admin_note' => 'Lengkap']);
        $this->assertSame('returned', $loan->fresh()->status);
        $this->assertSame(2, $item->fresh()->availableStock());
    }

    public function test_admin_can_crud_lab_and_item(): void
    {
        $admin = User::where('email', 'admin@elab.test')->first();
        $this->actingAs($admin)->post('/admin/labs', ['code' => 'LAB-X', 'name' => 'Lab X'])->assertRedirect();
        $labId = \App\Models\Lab::where('code', 'LAB-X')->value('id');
        $this->actingAs($admin)->post('/admin/items', ['lab_id' => $labId, 'code' => 'X-1', 'name' => 'Kamera', 'stock' => 2, 'condition' => 'baik'])->assertRedirect("/labs/{$labId}");
        $this->actingAs($admin)->get("/labs/{$labId}")->assertSee('Kamera');
        $this->actingAs($admin)->delete("/admin/labs/{$labId}")->assertRedirect('/labs');
        $this->assertDatabaseMissing('items', ['code' => 'X-1']);
    }
}
