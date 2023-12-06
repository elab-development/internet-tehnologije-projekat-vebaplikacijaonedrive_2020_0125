<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Firma;
use App\Models\User;
use App\Models\Zaposleni;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Factory methods:
        User::factory(10)->create();
        Firma::factory(3)->create();

        //Seeders:

        // User::create([
        //     'name' => 'Pera',
        //     'surname' => 'Peric',
        //     'email' => 'pera.peric@example.com',
        //     'password' => 'sifra'
        // ]);
        
        // $this->call([
        //     FirmaSeeder::class
        // ]);

        $this->call([
            ZaposleniSeeder::class
        ]);
    }
}
