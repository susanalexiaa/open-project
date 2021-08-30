<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Entity;

class Entities extends Component
{
    public $entities = [];
    protected $listeners = ['entityWasAdded' => 'mount', 'entityWasDeleted' => 'mount'];

    public function mount()
    {
        $company = Auth::user()->company;
        if( !is_null($company) ){
            $this->entities = $company->entities;
        }
    }

    public function render()
    {
        return view('livewire.entities');
    }
}
