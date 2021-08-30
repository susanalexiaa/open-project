<?php

namespace App\Http\Livewire;

use App\Domains\Contractor\Models\Contractor;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PhoneHelper;

class Contractors extends Component
{
    public $modalCreateContractor = false;
    public $modalChooseContractor = false;
    public $modalEditContractor = false;

    protected $listeners = [
                        'contractorChoose' => 'openModalChooseContractor',
                        'contractorHasBeenChosen' => 'contractorHasBeenChosen',
                        'startEditContractor' => 'openModalEditContractor'
                    ];

    public $contractor_id = null;

    // contractor vars

    public $title = null;
    public $phone = null;
    public $email = null;

    //
    public $usable_contractors;

    public function cleanVar()
    {
        $this->cleanContractorVar();
    }

    public function cleanContractorVar()
    {
        $this->title = null;
        $this->phone = null;
        $this->email = null;
    }

    public function getContractorVar()
    {
        return [
            'title' => $this->title,
            'phone' => PhoneHelper::getStandartisedNumber($this->phone),
            'email' => $this->email
        ];
    }

    public function openModalChooseContractor()
    {
        $this->modalChooseContractor = true;
    }

    public function closeModalChooseContractor()
    {
        $this->cleanVar();
        $this->modalChooseContractor = false;
    }

    public function openModalCreateContractor()
    {
        $this->modalCreateContractor = true;
    }

    public function openModalEditContractor($id)
    {
        $this->contractor_id = $id;

        $contractor = Contractor::find($id);
        $this->title = $contractor->title;
        $this->phone = $contractor->phone;
        $this->email = $contractor->email;

        $this->modalEditContractor = true;
    }

    public function closeModalEditContractor()
    {
        $this->cleanContractorVar();
        $this->contractor_id = null;
        $this->modalEditContractor = false;
    }

    public function closeModalCreateContractor()
    {
        $this->cleanContractorVar();
        $this->modalCreateContractor = false;
    }

    public function contractorHasBeenChosen($id)
    {
        $this->contractor_id = $id;
    }

    public function saveChosenContractor()
    {
        if( !is_null($this->contractor_id) ){
            $this->emit('contractorHasBeenSaved', $this->contractor_id);
            $this->closeModalChooseContractor();
        }
    }

    public function addContractor()
    {
        $contractor = Contractor::create( $this->getContractorVar() );

        $this->emit('contractorHasBeenSaved', $contractor->id);
        $this->emit('refreshLeadsCompnent');

        $this->closeModalCreateContractor();
        $this->closeModalChooseContractor();
    }

    public function getDuplicates()
    {
        if( !$this->phone && !$this->email ){ return []; }

        if( $this->phone ){
            $phone_duplicates = Contractor::where('phone', 'like', '%'.PhoneHelper::getStandartisedNumber($this->phone).'%' )->get();
        }

        if( $this->email ){
            $email_dublicates = Contractor::where('email', 'like', '%'.trim($this->email).'%' )->get();
        }

        if( $this->phone && $this->email ){
            $result = $phone_duplicates->merge($email_dublicates);
        }else{
            $result = $phone_duplicates ?? $email_dublicates;
        }

        return $result;
    }

    public function editContractor()
    {
        Contractor::find($this->contractor_id)->update( $this->getContractorVar() );

        $this->emit('refreshLeadsCompnent');
        $this->closeModalEditContractor();
    }

    public function render()
    {
        $vars = [];

        if( $this->modalCreateContractor ){
            $vars['duplicates'] = $this->getDuplicates();
        }

        return view('livewire.contractors', $vars);
    }

    public function mount()
    {
        $this->usable_contractors = Auth::user()->getMostUsableContractors();
    }

    public function chooseContractorFromDublicates($id)
    {
        $this->contractor_id = $id;
        $this->saveChosenContractor();
        $this->closeModalCreateContractor();
    }

    public function chooseContractorFromMostUsable($id)
    {
        $this->contractor_id = $id;
        $this->saveChosenContractor();
        $this->closeModalChooseContractor();
    }
}
