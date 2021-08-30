<?php

namespace App\Http\Livewire\Price;

use App\Domains\Price\Models\Price;
use App\Domains\Price\Models\PriceType;
use App\Traits\CustomLivewireValidate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class PriceTypeEdit extends Component
{

    use CustomLivewireValidate;

    public $priceType;

    public $success;

    public $errors;

    public $name, $date, $sort;

    protected $rules = [
        'name' => 'required'
    ];


    public function mount(PriceType $priceType){
        $this->priceType = $priceType->load('prices');
        $this->get();
    }

    public function render()
    {
        return view('livewire.price.price-type-edit');
    }

    public function update(){
        $this->clearResponse();
        $this->set();
        if ($this->validate()->fails())
            return false;
        $this->priceType->save();
        $this->dispatchBrowserEvent('rendering-list', ['new' => $this->priceType->wasRecentlyCreated]);
        $this->alert('success', '', __('prices.price_types.success_save'));
    }


    public function get(){
        $this->name = $this->priceType->name;
        $this->sort = $this->priceType->sort;
    }

    public function set(){
        $this->priceType->name = $this->name;
        $this->priceType->sort = $this->sort ?? 0;
    }

    public function clearResponse(){
        $this->success = null;
        $this->errors = [];
    }

    public function createPrice(){
        $this->clearResponse();
        if (empty($this->date)){
            return $this->addError('code', __('prices.prices.required_date'));
        }
        Price::create([
            'start_date' => Carbon::parse($this->date),
            'price_type_id' => $this->priceType->id,
        ]);
        $this->date = '';
        $this->priceType->load('prices');
    }
}
