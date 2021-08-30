<?php

namespace App\Http\Livewire\Admin\Teams;

use App\Models\Team;
use Livewire\Component;
use Livewire\WithPagination;

class Teams extends Component
{
    use WithPagination;

    public $search;

    public $oldSearch;

    public function render()
    {
        return view('livewire.admin.teams.teams', [
                'teams' => $this->getTeams()
            ]
        );
    }

    public function updatedSearch() {
        $this->gotoPage(1);
    }

    public function getTeams(){
        $query = Team::query();
        if (!empty($this->search)){

            $query = $query->where('name', 'like', '%' . $this->search . '%');
        }
        return $query->latest()->paginate(15);
    }
}
