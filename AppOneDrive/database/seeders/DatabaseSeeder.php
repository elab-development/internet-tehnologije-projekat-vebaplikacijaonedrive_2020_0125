<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Graph\OneDriveController;
use App\Models\Firm;
use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        
        $firms=Firm::all();
        $oneDrive=app(OneDriveController::class);
        foreach($firms as $firm)$oneDrive->createFirmFolder($firm->Name);

        //Seeders:

        $this->call([
            MemberSeeder::class
            
        ]);
        $firm=DB::table('firms')->orderBy('createdAt', 'asc')->first();
        $oneDrive=app(OneDriveController::class);
        $oneDrive->createFirmFolder($firm->Name);
    }
}
