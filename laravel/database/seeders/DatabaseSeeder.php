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
            'email' => 'mimin9@gmail.com',
            'nama' => 'Mimin no 9',
            'password' => Hash::make('12345678'),
            'role' => 'admn',
            'stat' => 'accepted'
        ]);
        // referrer
        User::create([
            'email' => 'galihakmal90@gmail.com',
            'nama' => 'Galih Akmal',
            'password' => Hash::make('12345678'),
            'role' => 'refl',
            'stat' => 'accepted',
            'kode_ref_saya' => 'asdfqw',
        ]);
        User::create([
            'email' => 'budicahyo@gmail.com',
            'nama' => 'Budi Cahyo',
            'password' => Hash::make('12345678'),
            'role' => 'refl',
            'stat' => 'accepted',
            'kode_ref_saya' => 'anomali',
        ]);
        // user account
        User::create([
            'email' => 'adinugroho12@gmail.com',
            'nama' => 'Adi Nugroho',
            'password' => Hash::make('12345678'),
            'stat' => 'accepted'
        ]);
        User::create([
            'email' => 'noxalfheim@gmail.com',
            'nama' => 'Nox Alfheim',
            'password' => Hash::make('12345678'),
        ]);
        User::create([
            'email' => 'johnbergstein@gmail.com',
            'nama' => 'John Bergstein',
            'password' => Hash::make('12345678'),
            'kode_ref' => 'asdfqw'
        ]);
        User::create([
            'email' => 'alicandra@gmail.com',
            'nama' => 'Ali Candra',
            'password' => Hash::make('12345678'),
            'kode_ref' => 'asdfqw',
            'stat' => 'accepted'
        ]);
    }
}
