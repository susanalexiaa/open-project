<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;

class RemoveBlankLeads extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leads = Lead::withTrashed()->get();

        foreach ($leads as $lead) {
            if( $lead->contractors->count() == 0 ){
                $lead->forceDelete();
            }
        }
    }
}
