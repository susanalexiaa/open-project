<?php

namespace App\Http\Livewire\Contractors;

use App\Domains\Contractor\Models\Contractor;
use App\Domains\Directory\Models\PhoneCall;
use Livewire\Component;
use Livewire\WithPagination;

class Calls extends Component
{
    use WithPagination;

    public $modalShowOneVisible = false;

    public $title, $email, $phone;

    public function render()
    {
        return view('livewire.contractors.calls', [
            'phoneCalls' => PhoneCall::orderByDesc('id')->paginate(24)
        ]);
    }

    public function openContractorOnAudio($id){

        $contractor = Contractor::query()->findOrFail($id);

        $this->title = $contractor->title;
        $this->email = $contractor->email;
        $this->phone = $contractor->phone;

        $this->modalShowOneVisible = true;
    }

    public function closeShowModal()
    {
        $this->modalShowOneVisible = false;
    }
}
