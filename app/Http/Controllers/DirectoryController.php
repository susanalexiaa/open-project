<?php

namespace App\Http\Controllers;

use App\Domains\Directory\Models\Locality;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{
    public function localities(){
        $list = Locality::query()->where('AOLEVEL', 1)
            ->select(['OFFNAME', 'id', 'REGIONCODE', 'AOGUID', 'AOLEVEL'])
            ->orderBy('OFFNAME')
            ->get();
        return view('locality.list', compact('list'));
    }
}
