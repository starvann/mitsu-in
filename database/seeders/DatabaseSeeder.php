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
        // User::create([
        //     'email' => 'test1@example.org',
        //     'nama' => 'Lorem Ipsum',
        //     'password' => Hash::make('12345678'),
        //     'stat' => 'accepted'
        // ]);
        // User::create([
        //     'email' => 'test2@example.org',
        //     'nama' => 'Lorem Dolor',
        //     'password' => Hash::make('12345678'),
        // ]);
        // User::create([
        //     'email' => 'test3@example.org',
        //     'nama' => 'Ipsum Lorem',
        //     'password' => Hash::make('12345678'),
        //     'role' => 'refl',
        //     'kode_ref_saya' => 'ipsum78',
        //     'stat' => 'accepted'
        // ]);
        // User::create([
        //     'email' => 'test4@example.org',
        //     'nama' => 'Ipsum Dolor',
        //     'password' => Hash::make('12345678'),
        //     'role' => 'refl',
        //     'kode_ref_saya' => 'lorem80',
        //     'stat' => 'accepted'
        // ]);
        // User::create([
        //     'email' => 'test5@example.org',
        //     'nama' => 'Dolor Lorem',
        //     'password' => Hash::make('12345678'),
        //     'kode_ref' => 'ipsum78',
        //     'stat' => 'accepted'
        // ]);
        // User::create(['email' => 'test2@example.org', 'nama' => 'Lorem Dolor', 'password' => Hash::make('12345678')]);
    }
}
