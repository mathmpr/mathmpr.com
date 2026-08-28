<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::updateOrCreate(
            ['email' => 'matheus@mathmpr.com'],
            [
                'name' => 'Matheus Prado',
                'password' => Hash::make('aquintem'),
            ]
        );
        // \App\Models\User::factory(10)->create();
    }
}
