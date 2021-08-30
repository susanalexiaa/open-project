<?php

namespace Database\Seeders;

use App\Domains\Directory\Models\MeasureUnit;
use Illuminate\Database\Seeder;

class MeasureUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MeasureUnit::updateOrCreate([
            'name' => 'шт.',
            'code' => '796'
        ]);
        MeasureUnit::updateOrCreate([
            'name' => 'упак.',
            'code' => '778'
        ]);
    }
}
