<?php

namespace Database\Seeders;

use App\Models\Firma;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirmaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Firma::create([
            'PIB' => 123456789,
            'name' => 'Firma LLC',
            'createdAt' => new DateTime()
        ]);
    }
}
