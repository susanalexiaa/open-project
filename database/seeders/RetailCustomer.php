<?php

namespace Database\Seeders;

use App\Domains\Contractor\Models\Contractor;
use Illuminate\Database\Seeder;

class RetailCustomer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Contractor::create([
            'title' => 'Розничный покупатель',
            'phone' => '+70000000000'
        ]);
    }
}
