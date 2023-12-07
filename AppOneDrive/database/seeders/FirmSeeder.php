<?php

namespace Database\Seeders;

use App\Models\Firm;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FirmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Firm::create([
            'PIB' => 123456789,
            'name' => 'Firma LLC',
            'createdAt' => new DateTime()
        ]);
    }
}
