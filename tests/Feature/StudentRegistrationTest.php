<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_register_a_student_who_can_then_log_in(): void
    {
        $data = [
            'name' => 'Mahasiswa Baru',
            'username' => 'MahasiswaBaru',
            'password' => 'uji123',
            'password_confirmation' => 'uji123',
            'role' => 'admin',
        ];
        $form = route('petugas.students.create');
        $submit = route('petugas.students.store');
        $this->get($form)->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create(['role' => 'mahasiswa']))
            ->post($submit, $data)->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $this->get($form)->assertOk();
        $this->post($submit, $data)->assertRedirect(route('petugas.dashboard'));
        $this->post($submit, $data)->assertSessionHasErrors('username');
        $this->assertDatabaseHas('users', ['username' => 'mahasiswabaru', 'role' => 'mahasiswa']);

        $this->post(route('logout'));
        $this->post(route('login.store'), [
            'access' => 'mahasiswa',
            'username' => $data['username'],
            'password' => $data['password'],
        ])->assertRedirect(route('mahasiswa.dashboard'));
    }
}
