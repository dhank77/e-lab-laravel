<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_catalog_from_login_without_admin_access(): void
    {
        Equipment::create([
            'name' => 'Multimeter',
            'category' => 'Alat ukur',
            'condition' => 'Baik',
            'available_quantity' => 2,
        ]);

        $this->get(route('login'))->assertOk()->assertSee(route('guest.catalog'));
        $this->get(route('guest.catalog'))->assertOk()
            ->assertSee('Multimeter')
            ->assertSee('2 unit tersedia')
            ->assertDontSee('Tambah Alat');
        $this->assertGuest();
        $this->get(route('petugas.equipment.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_save_equipment_and_view_its_photo(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('petugas.equipment.store'), [
            'name' => 'Multimeter',
            'category' => 'Alat ukur',
            'available_quantity' => 5,
            'condition' => 'Baik',
            'photo' => UploadedFile::fake()->image('multimeter.png'),
        ])->assertRedirect(route('petugas.equipment.index'));

        $this->assertDatabaseHas('equipment', [
            'name' => 'Multimeter',
            'category' => 'Alat ukur',
            'available_quantity' => 5,
            'condition' => 'Baik',
        ]);

        $equipment = Equipment::sole();
        Storage::disk('local')->assertExists($equipment->photo_path);

        $this->get(route('petugas.equipment.index'))
            ->assertOk()
            ->assertSee('Tambah Alat')
            ->assertSee('Multimeter')
            ->assertSee('5 unit tersedia');

        $this->get(route('petugas.equipment.photo', $equipment))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->actingAs(User::factory()->create(['role' => 'mahasiswa']))
            ->get(route('petugas.equipment.photo', $equipment))
            ->assertForbidden();
    }

    public function test_admin_can_view_edit_and_delete_equipment(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        $equipment = Equipment::create([
            'name' => 'Multimeter',
            'category' => 'Alat ukur',
            'available_quantity' => 2,
            'condition' => 'Baik',
        ]);

        $this->get(route('petugas.equipment.show', $equipment))->assertOk()->assertSee('Multimeter');
        $this->get(route('petugas.equipment.edit', $equipment))->assertOk()->assertSee('Edit Barang');

        $data = [
            'name' => 'Multimeter digital',
            'category' => 'Alat ukur',
            'available_quantity' => 3,
            'condition' => 'Rusak',
        ];
        $this->put(route('petugas.equipment.update', $equipment), $data)->assertSessionHasErrors('condition');

        $data['condition'] = 'Buruk';
        $this->put(route('petugas.equipment.update', $equipment), $data)->assertRedirect(route('petugas.equipment.index'));
        $this->assertDatabaseHas('equipment', $data);

        $this->delete(route('petugas.equipment.destroy', $equipment))->assertRedirect(route('petugas.equipment.index'));
        $this->assertModelMissing($equipment);
    }

    public function test_equipment_creation_requires_admin_and_valid_stock(): void
    {
        $route = route('petugas.equipment.store');
        $data = [
            'name' => 'Multimeter',
            'category' => 'Alat ukur',
            'available_quantity' => -1,
            'condition' => 'Baik',
        ];

        $this->post($route, $data)->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => 'mahasiswa']))
            ->post($route, $data)->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'petugas']))
            ->get(route('petugas.dashboard'))->assertForbidden();

        $this->post($route, $data)->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post($route, $data)->assertSessionHasErrors('available_quantity');

        $this->assertDatabaseCount('equipment', 0);
    }
}
