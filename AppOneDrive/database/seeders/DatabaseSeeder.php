<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Firm;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Factory methods:
        User::factory(3)->create();
        Firm::factory(2)->create();
        

        //Seeders:

        $this->call([
            MemberSeeder::class
        ]);
    }
}
