<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class MergeContractors
{

    public static function merge($original, $dublicate)
    {
        $phoneCalls = $dublicate->phoneCalls;

        foreach($phoneCalls as $phoneCall){
            $phoneCall->contractor_id = $original->id;
            $phoneCall->save();
        }

        $emails = $dublicate->emails;
        foreach($emails as $email){
            $email->contractor_id = $original->id;
            $email->save();
        }

        DB::table('contractor_lead')
            ->where('contractor_id', $dublicate->id)
            ->update(['contractor_id' => $original->id]);

        if( !str_contains($dublicate->title, 'Неизвестный') ){

            if( str_contains($original->title, 'Неизвестный') ){
                $original->title = $dublicate->title;
            }else{
                if( strlen($dublicate->title) > strlen($original->title) ){
                    $original->title = $dublicate->title;
                }
            }
            
            $original->save();
        }

        if( !is_null($dublicate->email) && is_null($original->email) ){
            $original->email = $dublicate->email;
            $original->save();
        }

        $dublicate->forceDelete();
    }
}