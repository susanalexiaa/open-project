<?php

namespace App\Http\Controllers;

use App\Domains\Integration\Models\TelephonyIntegration;
use Illuminate\Http\Request;
use App\Helpers\Zadarma\Webhook;


class TelephonyController extends Controller
{
    public function acceptRequest( $id, Request $request ): int
    {
        if ( $request->zd_echo ) exit( $request->zd_echo );

        $integration = TelephonyIntegration::query()->find($id);

        if( $integration->is_active ) {
            $webhook = new Webhook($integration->key, $integration->secret);

            $webhook->acceptCall($request);
        }

        return 1;
    }
}
