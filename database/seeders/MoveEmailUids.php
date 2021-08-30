<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Domains\Directory\Models\Emailuid;

class MoveEmailUids extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emails = Emailuid::all();

        foreach( $emails as $email ){
            DB::table('emailuids2')->insert([
                'emailuid' => $email->emailuid,
                'email' => $email->email
            ]);
        }
    }
}
