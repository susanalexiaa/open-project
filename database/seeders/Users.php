<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::find(1) === null) {
            if ( Team::find(1) === null) {
                $team = Team::create([
                    'id' => 1,
                    'user_id' => 1,
                    'name' => "Admin's Team",
                    'personal_team' => 1
                ]);
            }

            $password = Hash::make("1231qaz!");

            User::create([
                'id' => 1,
                'name' => "Admin",
                'email' => "admin@admin.com",
                'password' => $password,
                'current_team_id' => 1,
                'recording_allowed' => 1,
                'position' => "Менеджер",
                'timezone' => 'Europe/Moscow'
            ]);
        }

    }
}
