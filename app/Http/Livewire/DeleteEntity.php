<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DeleteEntity extends Component
{
    public $confirmingEntityDeletion = false;
    public $enity_id;

    protected $listeners = ['openDeleteEntityModal'];

    public function render()
    {
        return view('livewire.delete-entity');
    }

    public function openDeleteEntityModal($id)
    {
        $this->enity_id = $id;
        $this->confirmingEntityDeletion = true;
    }

    public function closeDeleteEntityModal()
    {
        $this->confirmingEntityDeletion = false;
    }

    public function deleteEntity()
    {
        $company = Auth::user()->company;
        $entity = $company->entities()->where('id', $this->enity_id)->firstOrFail();
        $entity->delete();

        $this->closeDeleteEntityModal();
        $this->emit('entityWasDeleted');
    }
}
