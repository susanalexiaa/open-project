<?php

namespace App\Helpers\Zadarma;

use Zadarma_API\Api;
use Zadarma_API\Client;
use Zadarma_API\Webhook\AbstractNotify;
use Zadarma_API\Webhook\NotifyIvr;
use Zadarma_API\Webhook\NotifyStart;
use Zadarma_API\Webhook\NotifyRecord;
use Zadarma_API\Webhook\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;
use App\Domains\Contractor\Models\Contractor;
use App\Domains\Contractor\Models\ContractorLead;
use App\Models\PhoneCall;
use App\Models\CallIdAndCallerId;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class Webhook {
    private $api;

    public function __construct($key, $secret)
    {
        $this->api = new Api($key, $secret, false);
    }

    public function acceptCall($request)
    {
        $notify_record = $this->getEvent([AbstractNotify::EVENT_RECORD]);
        $notify_end = $this->getEvent([AbstractNotify::EVENT_END]);
        $notify_out_end = $this->getEvent([AbstractNotify::EVENT_OUT_END]);

        if ( $notify_record ) {

            $callIdWithRec = $notify_record->call_id_with_rec;
            $result = $this->api->getPbxRecord($callIdWithRec, null, 5184000);

            CallIdAndCallerId::cleanUnused();

            $record = CallIdAndCallerId::where('callIdWithRec', $callIdWithRec)->firstOrFail();

            $phone = $record->callerId;
            $contractor = Contractor::findOrCreateByPhone($phone);

            $contents = file_get_contents( $result->link );
            $name = substr( $result->link, strrpos($result->link, '/') + 1);

            $path = 'audio/'.$name;
            Storage::put($path, $contents);

            PhoneCall::create([
                'type' => $record->type,
                'contractor_id' => $contractor->id,
                'link' => $path,
                'disposition' => "answered"
            ]);
        }

        if( $notify_end && $notify_end->disposition == "answered" ){
            $this->makeTempUntilRecord($notify_end, 'Входящий');
        }else if( $notify_out_end && $notify_out_end->disposition == "answered" ){
            $this->makeTempUntilRecord($notify_out_end, 'Исходящий');
        }
        else if( $notify_end ){
            $this->makeUnsuccessfulPhoneCall($notify_end, 'Входящий');
        }else if( $notify_out_end ){
            $this->makeUnsuccessfulPhoneCall($notify_out_end, 'Исходящий');
        }
    }

    private function getEvent($allowedTypes)
    {
        return $this->api->getWebhookEvent($allowedTypes);
    }

    private function makeTempUntilRecord($notify, $type)
    {
        CallIdAndCallerId::create([
            'callIdWit
            hRec' => $notify->call_id_with_rec,
            'callerId' => $notify->caller_id,
            'type' => $type,
            'until' => Carbon::now()->addMinutes(1)
        ]);
    }

    private function makeUnsuccessfulPhoneCall($notify, $type)
    {
        $phone = $notify->caller_id;
        $contractor = Contractor::findOrCreateByPhone($phone);

        PhoneCall::create([
            'type' => $type,
            'contractor_id' => $contractor->id,
            'disposition' => $notify->disposition
        ]);
    }
}
