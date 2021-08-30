<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function sales( Request $request )
    {
        return view('reports.sales');
    }
}
