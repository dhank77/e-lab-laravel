<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('dashboard'));
        $this->get(route('mahasiswa.dashboard'))->assertRedirect(route('login'));
        $this->get(route('petugas.dashboard'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('Masuk ke E-Lab');
    }

    #[DataProvider('roles')]
    public function test_each_role_can_login_access_its_dashboard_and_logout(string $role, string $otherRole): void
    {
        $user = User::factory()->create(['role' => $role]);
        $dashboard = $role === 'admin' ? 'petugas' : $role;

        $this->post(route('login.store'), [
            'username' => strtoupper($user->username),
            'password' => 'password',
            'role' => $otherRole,
            'access' => $dashboard,
        ])->assertRedirect(route($dashboard.'.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get(route($dashboard.'.dashboard'))->assertOk()->assertSee($user->name);
        $this->get(route($otherRole.'.dashboard'))->assertForbidden();

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
        $this->get(route($dashboard.'.dashboard'))->assertRedirect(route('login'));
    }

    public function test_repeated_incorrect_passwords_temporarily_block_login(): void
    {
        $user = User::factory()->create();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->from(route('login'))->post(route('login.store'), [
                'username' => $user->username,
                'password' => 'incorrect',
            ])->assertSessionHasErrors(['username' => 'User Name atau Password salah.']);
        }

        $this->from(route('login'))->post(route('login.store'), [
            'username' => $user->username,
            'password' => 'password',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public static function roles(): array
    {
        return [
            'mahasiswa' => ['mahasiswa', 'petugas'],
            'admin' => ['admin', 'mahasiswa'],
        ];
    }
}
