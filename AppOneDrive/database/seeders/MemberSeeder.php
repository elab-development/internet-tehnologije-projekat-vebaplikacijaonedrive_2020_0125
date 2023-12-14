<?php

namespace Database\Seeders;

use App\Models\Firm;
use App\Models\Member;
use App\Models\User;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users=User::factory(3)->create();
        $firm=Firm::factory()->create();
        foreach($users as $u){
                Member::create([
                    'user_id' => $u->id,
                    'firm_pib' => $firm->PIB,
                    'addedAt' => new DateTime(),
                    'privileges' => collect(['Read','Write'])->random()
                ]);
        }
    }
}
