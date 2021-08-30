<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContractorController extends Controller
{
    public function index()
    {
        return view('contractors');
    }

    public function calls()
    {
        return view('contractors_calls');
    }
}
