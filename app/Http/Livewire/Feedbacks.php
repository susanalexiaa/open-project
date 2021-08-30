<?php

namespace App\Http\Livewire;

use App\Domains\Directory\Models\Entity;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Domains\Feedback\Models\Feedback;

class Feedbacks extends Component
{
    use WithPagination;

    public function render()
    {
        $company = Auth::user()->company;
        $feedbacks = [];

        if( !is_null($company) ){
            $entities_id = Entity::where('company_id', $company->id)->pluck('id')->toArray();
            $feedbacks = Feedback::whereIn('entity_id', $entities_id)->orderByDesc('id')->paginate(10);
        }
        return view('livewire.feedbacks', [
            'feedbacks' => $feedbacks,
        ]);
    }
}
