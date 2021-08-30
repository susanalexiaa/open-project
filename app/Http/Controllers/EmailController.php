<?php

namespace App\Http\Controllers;

use App\Domains\Contractor\Models\EmailFromAccountToContractor;

class EmailController extends Controller
{
    public function email($id)
    {
        return view('email', [
            'html' => EmailFromAccountToContractor::query()->find($id)->content
        ]);
    }
}
