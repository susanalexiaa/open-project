<?php

namespace Database\Seeders;

use App\Domains\Lead\Models\LeadSource;
use Illuminate\Database\Seeder;

class LeadSources extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = ['Реклама', 'Не определено'];

        foreach( $names as $name ){
            LeadSource::create([ 'name' => $name ]);
        }
    }
}
