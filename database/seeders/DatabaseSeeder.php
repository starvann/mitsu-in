<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // admin account
        User::create([
            'email' => 'mimin@mitsu-in.com',
            'nama' => 'Mimin',
            'password' => Hash::make('${N1-Ustim?}'),
            'role' => 'admn',
            'stat' => 'accepted'
        ]);
    }
}
