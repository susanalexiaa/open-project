<?php

namespace App\Http\Livewire\Admin;

use App\Domains\Lead\Models\LeadStatus;
use Livewire\Component;

class Statuses extends Component
{

    public $modalFormVisible = false;
    public $confirmingUserDeletion = false;
    public $modalId;
    public $name;
    public $color_class;
    public $order;
    public $is_active;

    public function render()
    {
        return view('livewire.admin.statuses', [
            "data" => $this->read(),
        ]);
    }

    public function cleanVar()
    {
        $this->modalId = null;
        $this->name = '';
        $this->color_class = '';
        $this->order = null;
        $this->is_active = null;
    }

    public function modelData()
    {
        return [
            'name' => $this->name,
            'color_class' => $this->color_class,
            'order' => $this->order,
            'is_active' => $this->is_active
        ];
    }

    public function createShowModal()
    {
        $this->resetValidation();
        $this->cleanVar();
        $this->modalFormVisible = true;
    }

    public function updateShowModal($id)
    {
        $this->cleanVar();
        $this->resetValidation();
        $this->modalId = $id;
        $this->modalFormVisible = true;
        $this->loadData();
    }

    public function create()
    {
        LeadStatus::create($this->modelData());

        $this->cleanVar();
        $this->modalFormVisible = false;
    }

    public function read()
    {
        return LeadStatus::paginate(5);
    }

    private function loadData()
    {
        $data = LeadStatus::find($this->modalId);
        $this->name = $data->name;
        $this->color_class = $data->color_class;
        $this->order = $data->order;
        $this->is_active = $data->is_active;
    }

    public function update()
    {
        $status = LeadStatus::find($this->modalId);
        $status->update($this->modelData());
        $this->modalFormVisible = false;
    }

    public function deleteShowModal($id)
    {
        $this->cleanVar();
        $this->resetValidation();
        $this->modalId = $id;
        $this->confirmingUserDeletion = true;
    }

    public function delete()
    {
        $status = LeadStatus::find($this->modalId);
        $status->delete();
        $this->confirmingUserDeletion = false;
    }
}
