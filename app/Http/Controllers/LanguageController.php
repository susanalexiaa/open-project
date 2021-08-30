<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function changeLanguage( $slug, Request $request )
    {
        $request->session()->put('locale', $slug);
        return redirect()->back();
    }
}
