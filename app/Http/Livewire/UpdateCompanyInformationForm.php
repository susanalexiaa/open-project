<?php

namespace App\Http\Livewire;

use App\Domains\Directory\Models\Company;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdateCompanyInformationForm extends Component
{

    public $name, $phone;

    public function mount()
    {
        $company = Auth::user()->company;

        if( !is_null($company)) {
            $this->name = $company->name;
            $this->phone = $company->phone;
        }
    }

    public function updateCompanyInformation()
    {
        $user = Auth::user();
        $company = $user->company;

        if( is_null($company) ) {
            Company::create([
                'name' => $this->name,
                'user_id' => $user->id,
                'phone' => $this->phone
            ]);
        }else{
            $company->name = $this->name;
            $company->phone = $this->phone;
            $company->save();
        }
    }

    public function render()
    {
        return view('livewire.update-company-information-form');
    }
}
