<?php

namespace App\Http\Controllers;

use App\Domains\Employees\Models\WorkingCell;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Domains\Lead\Models\LeadStatus;
use App\Domains\Lead\Models\Lead;

use App\Jobs\GrabEmailsByConnectedToAccounts;

class LeadsController extends Controller
{
    public function leads()
    {
        return view('dashboard');
    }

    public function create()
    {
        return view('lead-add', [
            'statuses' => LeadStatus::all()
        ]);
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'lead-trixFields.description' => 'required',
            'status_id' => 'required',
            'source' => 'required'
        ]);

        Lead::create([
            'source' => $request->source,
            'user_id' => $request->user()->id,
            'status_id' => $request->status_id,
            'lead-trixFields' => request('lead-trixFields'),
            'attachment-lead-trixFields' => request('attachment-lead-trixFields')
        ]);

        return redirect()->back();
    }

    public function test()
    {
//        GrabEmailsByConnectedToAccounts::dispatch();
        return 123;
    }
}
