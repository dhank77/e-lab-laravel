<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElabDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->upsert([
            [
                'id' => 1,
                'name' => 'Hamdika',
                'email' => 'mahasiswa@elab.test',
                'email_verified_at' => null,
                'password' => '$2y$12$dD6Y8ZxksxifTafLVnVy3eDEOlKbjAQczWoBSozxKCr/cc1BGidk.',
                'remember_token' => null,
                'created_at' => '2026-11-10 10:39:33',
                'updated_at' => '2026-11-11 08:08:10',
                'role' => 'mahasiswa',
                'username' => 'hamdika',
            ],
            [
                'id' => 2,
                'name' => 'Petugas Demo',
                'email' => 'petugas@elab.test',
                'email_verified_at' => null,
                'password' => '$2y$12$SKVUrgdnCRBLU.KHiHlPleybOBQ0ZOszmSv1GZ4pY4AsqO013cW.m',
                'remember_token' => null,
                'created_at' => '2026-11-10 10:39:33',
                'updated_at' => '2026-11-10 10:43:38',
                'role' => 'petugas',
                'username' => 'petugas',
            ],
            [
                'id' => 3,
                'name' => 'Admin',
                'email' => 'admin@elab.test',
                'email_verified_at' => null,
                'password' => '$2y$12$KN/Tn/xfCpnOmyb4RqONNebXEdQiHVXN.fcl5zm8Ca2R.lqp2KCsK',
                'remember_token' => null,
                'created_at' => '2026-11-10 10:43:38',
                'updated_at' => '2026-11-10 11:02:44',
                'role' => 'admin',
                'username' => 'admin',
            ],
        ], ['id'], ['name', 'email', 'password', 'role', 'username', 'updated_at']);

        DB::table('equipment')->upsert([
            ['id' => 1, 'name' => 'Sensor DHT22', 'category' => 'Alat ukur', 'available_quantity' => 8, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:13:23', 'updated_at' => '2026-11-11 07:13:23'],
            ['id' => 2, 'name' => 'Sensor Ultrasonik HC-SR04', 'category' => 'Alat ukur', 'available_quantity' => 10, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:14:25', 'updated_at' => '2026-11-11 07:14:25'],
            ['id' => 3, 'name' => 'Sensor Gas MQ-2', 'category' => 'Alat ukur', 'available_quantity' => 6, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:14:49', 'updated_at' => '2026-11-11 07:14:49'],
            ['id' => 4, 'name' => 'ESP32 DevKit V1', 'category' => 'Komputer', 'available_quantity' => 12, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:15:52', 'updated_at' => '2026-11-11 07:15:52'],
            ['id' => 5, 'name' => 'Headset VR Meta Quest 2', 'category' => 'Komputer', 'available_quantity' => 2, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:16:12', 'updated_at' => '2026-11-11 07:16:12'],
            ['id' => 6, 'name' => 'Spidol papan tulis', 'category' => 'Alat ukur', 'available_quantity' => 15, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:16:30', 'updated_at' => '2026-11-11 07:16:30'],
            ['id' => 7, 'name' => 'Baterai AA', 'category' => 'Komputer', 'available_quantity' => 23, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:17:05', 'updated_at' => '2026-11-11 08:50:46'],
            ['id' => 8, 'name' => 'Multimeter digital DT830B', 'category' => 'Komputer', 'available_quantity' => 6, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:17:21', 'updated_at' => '2026-11-11 07:17:21'],
            ['id' => 9, 'name' => 'Breadboard 830 titik', 'category' => 'Alat ukur', 'available_quantity' => 10, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:17:39', 'updated_at' => '2026-11-11 07:17:39'],
            ['id' => 10, 'name' => 'Kabel jumper set', 'category' => 'Alat ukur', 'available_quantity' => 15, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:18:01', 'updated_at' => '2026-11-11 07:18:01'],
            ['id' => 11, 'name' => 'LED 5 mm', 'category' => 'Komputer', 'available_quantity' => 50, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:18:34', 'updated_at' => '2026-11-11 07:18:34'],
            ['id' => 12, 'name' => 'Resistor kit', 'category' => 'Komputer', 'available_quantity' => 10, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:18:52', 'updated_at' => '2026-11-11 07:18:52'],
            ['id' => 13, 'name' => 'Solder listrik', 'category' => 'Alat ukur', 'available_quantity' => 4, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:19:13', 'updated_at' => '2026-11-11 07:19:13'],
            ['id' => 14, 'name' => 'Kabel USB Type-C', 'category' => 'Alat ukur', 'available_quantity' => 12, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:19:27', 'updated_at' => '2026-11-11 07:19:27'],
            ['id' => 15, 'name' => 'Tang potong elektronik', 'category' => 'Alat ukur', 'available_quantity' => 5, 'condition' => 'Baik', 'photo_path' => null, 'created_at' => '2026-11-11 07:19:53', 'updated_at' => '2026-11-11 07:19:53'],
            ['id' => 16, 'name' => 'Laptop', 'category' => 'Komputer', 'available_quantity' => 20, 'condition' => 'Buruk', 'photo_path' => null, 'created_at' => '2026-11-11 07:20:06', 'updated_at' => '2026-11-11 07:20:06'],
        ], ['id'], ['name', 'category', 'available_quantity', 'condition', 'photo_path', 'updated_at']);

        DB::table('loan_requests')->upsert([
            [
                'id' => 1,
                'user_id' => 1,
                'equipment_id' => 7,
                'equipment_name' => 'Baterai AA',
                'quantity' => 1,
                'borrow_date' => '2026-11-12',
                'return_date' => '2026-11-13',
                'purpose' => 'Peminjaman',
                'status' => 'dipinjam',
                'created_at' => '2026-11-11 08:50:00',
                'updated_at' => '2026-11-11 08:50:46',
                'rejection_reason' => null,
                'handed_over_at' => '2026-11-11 08:50:46',
                'returned_at' => null,
                'return_condition' => null,
            ],
        ], ['id'], ['user_id', 'equipment_id', 'equipment_name', 'quantity', 'borrow_date', 'return_date', 'purpose', 'status', 'updated_at', 'rejection_reason', 'handed_over_at', 'returned_at', 'return_condition']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), COALESCE((SELECT MAX(id) FROM users), 1))");
            DB::statement("SELECT setval(pg_get_serial_sequence('equipment', 'id'), COALESCE((SELECT MAX(id) FROM equipment), 1))");
            DB::statement("SELECT setval(pg_get_serial_sequence('loan_requests', 'id'), COALESCE((SELECT MAX(id) FROM loan_requests), 1))");
        }
    }
}
