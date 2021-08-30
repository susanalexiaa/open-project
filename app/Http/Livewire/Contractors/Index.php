<?php

namespace App\Http\Livewire\Contractors;

use Livewire\Component;
use App\Domains\Contractor\Models\Contractor;
use Livewire\WithPagination;


class Index extends Component
{
    use WithPagination;

    public $modalNewOneVisible = false;
    public $modalEditOneVisible = false;
    public $confirmingUserDeletion = false;

    public $key_search;

    public $contractor;

    public $title;
    public $phone;
    public $email;

    public function render()
    {
        if( $this->key_search ){
            $contractors = Contractor::search($this->key_search)->orderBy('title');
            $this->gotoPage(1);
        }else{
            $contractors = Contractor::orderBy('title');
        }

        return view('livewire.contractors.index', [
            'contractors' => $contractors->paginate(10)
        ]);
    }

    public function cleanVar()
    {
        $this->contractor = null;

        $this->title = null;
        $this->phone = null;
        $this->email = null;
    }

    public function deleteShowModal($id)
    {
        $this->contractor = Contractor::find($id);
        $this->confirmingUserDeletion = true;
    }

    public function editShowModal($id)
    {
        $contractor = Contractor::find($id);
        $this->loadVars($contractor);

        $this->modalEditOneVisible = true;
    }

    public function editHideModal()
    {
        $this->cleanVar();
        $this->modalEditOneVisible = false;
    }

    public function delete()
    {
        $this->contractor->delete();

        $this->confirmingUserDeletion = false;
        $this->cleanVar();
    }

    public function getVars()
    {
        return [
            'title' => $this->title,
            'email' => $this->email,
            'phone' => $this->phone
        ];
    }

    public function loadVars($obj)
    {
        $this->contractor = $obj;

        $this->title = $obj->title;
        $this->email = $obj->email;
        $this->phone = $obj->phone;
    }

    public function createOne()
    {
        Contractor::create( $this->getVars() );

        $this->cleanVar();
        $this->modalNewOneVisible = false;
    }

    public function updateOne()
    {
        $this->contractor->update( $this->getVars() );

        $this->cleanVar();
        $this->modalEditOneVisible = false;
    }


}
