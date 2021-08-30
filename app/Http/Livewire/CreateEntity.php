<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;


class CreateEntity extends Component
{
    public $name = '';

    public $isAddEntityModalOpen;
    protected $listeners = ['openAddEntityModal'];

    public function render()
    {
        return view('livewire.create-entity');
    }

    public function closeAddEntityModal()
    {
        $this->isAddEntityModalOpen = false;
    }

    public function openAddEntityModal()
    {
        $this->isAddEntityModalOpen = true;
    }

    public function clearForm()
    {
        $this->name = '';
    }

    public function addEntity()
    {
        $company = Auth::user()->company;
        $company->createNewEntity($this->name);
        $this->clearForm();
        $this->closeAddEntityModal();
        $this->emit('entityWasAdded');
    }
}
