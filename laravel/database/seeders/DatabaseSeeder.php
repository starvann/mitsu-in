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
            'email' => 'firstadmin@domain.org',
            'password' => Hash::make('12345678'),
            'role' => 'admn'
        ]);
        // user account
        User::create([
            'email' => 'adinugroho12@gmail.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
