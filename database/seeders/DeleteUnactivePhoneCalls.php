<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhoneCall;

class DeleteUnactivePhoneCalls extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PhoneCall::where('link', 'like', '%https://api.zadarma.com/v1/pbx/record/download/%')->delete();
    }
}
