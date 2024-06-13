<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'name' => 'Matheus Prado',
            'email' => 'matheus@mathmpr.com',
            'password' => Hash::make('aquintem')
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
