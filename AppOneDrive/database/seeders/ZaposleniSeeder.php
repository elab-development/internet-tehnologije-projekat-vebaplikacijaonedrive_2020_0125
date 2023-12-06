<?php

namespace Database\Seeders;

use App\Models\Firma;
use App\Models\User;
use App\Models\Zaposleni;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZaposleniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $firms = Firma::all();

        $user = collect($users)->random();
        $firm = collect($firms)->random();
        Zaposleni::create([
            'user_id' => $user->id,
            'firma_pib' => $firm->PIB,
            'addedAt' => new DateTime(),
            'privileges' => 'Admin'
        ]);

        foreach($users as $u){
            if($u->id != $user->id){
                Zaposleni::create([
                    'user_id' => $user->id,
                    'firma_pib' => $firm->PIB,
                    'addedAt' => new DateTime(),
                    'privileges' => collect(['Read', 'Write', 'Admin'])->random()
                ]);
            }
        }
    }
}
