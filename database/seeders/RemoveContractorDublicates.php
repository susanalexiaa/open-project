<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Contractor;
use Illuminate\Support\Facades\Log;
use App\Helpers\MergeContractors;

class RemoveContractorDublicates extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // +7 phones 

        $contractors = Contractor::where([
            ['phone', 'like', '%+%']
        ])->take(100)->get();

        foreach ($contractors as $contractor) {
            $phone = str_replace('+', '', $contractor->phone);
            $original = Contractor::where('phone', $phone)->first();

            if( !is_null($original) ){
                MergeContractors::merge($original, $contractor);
            }else{
                $contractor->phone = $phone;
                $contractor->update();
            }
        }

        $out = Log::channel('stderr');

        $out->info("+7 status: ");
        if( is_null($contractors->first()) ){
            $out->info("Success");
        }else{
            $out->info("First - ".$contractors->first()->id);
            $out->info("Last - ". $contractors->last()->id);
        }

        if( !is_null($contractors->first()) ) return;

        // 8 phones
        
        $contractors = Contractor::where([
            ['phone', 'regexp', '^8.+']
        ])->take(100)->get();

        $ids = $contractors->pluck('id')->toArray();

        foreach ($contractors as $contractor) {
            $phone = substr($contractor->phone, 1);
            $original = Contractor::where('phone', 'like', '%'.$phone.'%')->whereNotIn('id', $ids)->first();

            if( !is_null($original) ){
                MergeContractors::merge($original, $contractor);
            }else{
                $contractor->phone = "7".$phone;
                $contractor->update();
            }
        }

        $out->info("8 status: ");
        if( is_null($contractors->first()) ){
            $out->info("Success");
        }else{
            $out->info("First - ".$contractors->first()->id);
            $out->info("Last - ". $contractors->last()->id);
        }

    }
}
