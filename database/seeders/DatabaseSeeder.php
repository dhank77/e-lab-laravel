<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Lab;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Lab',
            'email' => 'admin@elab.test',
            'password' => 'password',
        ]);
        $admin->forceFill(['role' => 'admin'])->save();

        $mahasiswa = User::create([
            'name' => 'Mahasiswa Demo',
            'nim' => 'D121231001',
            'email' => 'mahasiswa@elab.test',
            'phone' => '081234567890',
            'password' => 'password',
        ]);

        $labs = [
            ['code' => 'LAB-RPL', 'name' => 'Laboratorium Rekayasa Perangkat Lunak', 'location' => 'Gedung A Lt. 2', 'head' => 'Dr. Andi Saputra',
                'items' => [
                    ['Laptop Lenovo ThinkPad', 'Komputer', 10],
                    ['Proyektor Epson', 'Multimedia', 2],
                    ['Kabel HDMI 5m', 'Aksesoris', 8],
                ]],
            ['code' => 'LAB-JAR', 'name' => 'Laboratorium Jaringan Komputer', 'location' => 'Gedung A Lt. 3', 'head' => 'Ir. Budi Hartono, M.T.',
                'items' => [
                    ['Router MikroTik RB951', 'Jaringan', 6],
                    ['Switch Cisco 24 Port', 'Jaringan', 3],
                    ['Crimping Tool RJ45', 'Peralatan', 12],
                    ['LAN Tester', 'Peralatan', 5],
                ]],
            ['code' => 'LAB-ELK', 'name' => 'Laboratorium Elektronika', 'location' => 'Gedung B Lt. 1', 'head' => 'Dr. Citra Lestari',
                'items' => [
                    ['Arduino Uno R3', 'Mikrokontroler', 15],
                    ['Multimeter Digital', 'Alat Ukur', 8],
                    ['Osiloskop Rigol', 'Alat Ukur', 2],
                    ['Solder Station', 'Peralatan', 6],
                ]],
        ];

        foreach ($labs as $data) {
            $items = $data['items'];
            unset($data['items']);
            $lab = Lab::create($data + ['description' => 'Fasilitas praktikum untuk mahasiswa.']);

            foreach ($items as $i => [$name, $category, $stock]) {
                $lab->items()->create([
                    'code' => $lab->code.'-'.str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'name' => $name,
                    'category' => $category,
                    'stock' => $stock,
                    'condition' => 'baik',
                ]);
            }
        }

        Loan::create([
            'user_id' => $mahasiswa->id,
            'item_id' => Item::where('name', 'Arduino Uno R3')->value('id'),
            'quantity' => 2,
            'loan_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'purpose' => 'Tugas praktikum mikrokontroler',
            'status' => 'pending',
        ]);
    }
}
