<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $accounts = [
            'mahasiswa' => ['Hamdika', 'mahasiswa', '123'],
            'admin' => ['Admin', 'admin', '123'],
        ];

        foreach ($accounts as $username => [$name, $role, $password]) {
            $user = User::firstOrNew(['email' => $username.'@elab.test']);

            if (! $user->exists) {
                $user->name = $name;
                $user->role = $role;
                $user->password = $password;
            }

            if ($username === 'mahasiswa' && $user->role === 'mahasiswa' && in_array($user->username, [null, 'mahasiswa'], true)) {
                $user->name = $name;
                $user->username = 'hamdika';
                $user->password = $password;
            }

            if ($user->username === null) {
                $user->username = $username;
            }

            if ($username === 'admin' && $user->username === 'admin' && $user->role === 'petugas') {
                $user->role = 'admin';
            }

            $user->save();
        }
    }
}
