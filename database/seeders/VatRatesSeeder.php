<?php

namespace Database\Seeders;

use App\Domains\Directory\Models\VatRate;
use Illuminate\Database\Seeder;

class VatRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VatRate::firstOrCreate([
            'name' => '20%',
        ]);
        VatRate::firstOrCreate([
            'name' => '20% / 120%',
        ]);
        VatRate::firstOrCreate([
            'name' => '10%',
        ]);
        VatRate::firstOrCreate([
            'name' => '0%',
        ]);
        VatRate::firstOrCreate([
            'name' => '10% / 110%',
        ]);
        VatRate::firstOrCreate([
            'name' => 'Без НДС',
        ]);
    }
}
