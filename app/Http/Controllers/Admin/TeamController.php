<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return view('admin.teams.index');
    }

    public function destroy(Team $team)
    {
        $team->purge();

        return redirect(route('admin.teams'));
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function create()
    {
        $team = new Team();

        return view('admin.teams.edit', compact('team'));
    }

    public function selectable(){
        $class = Team::class;
        $searchAttribute = 'name';
        $storageName = 'team_id';
        return view('vendor.list-livewire', compact('class', 'searchAttribute', 'storageName'));
    }
}

