<?php

namespace App\Http\Livewire\Price;

use App\Traits\CustomLivewireValidate;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class PriceEdit extends Component
{

    use CustomLivewireValidate;


    public $price, $success, $errors;

    public $priceInput;
    public $nomenclature_id;

    protected function validationAttributes(): array
    {
        return [
            'priceInput' => __('prices.price'),
            'nomenclature_id' => __('nomenclatures.nomenclature')
        ];
    }

    protected $rules = [
        'priceInput' => ['required', 'integer'],
        'nomenclature_id' => ['required', 'integer']
    ];
//    public function mount(Price $price){
//        $this->price = $price;
//    }

    public function render()
    {
        return view('livewire.price.price-edit');
    }

    public function addPrice(){
        if ($this->validate()->fails()){
            return false;
        }
        if ($this->price->products->where('id', $this->nomenclature_id)->isNotEmpty()){
            return $this->addError('code', __('prices.prices.already_attach'));
        }
        $this->price->products()->attach($this->nomenclature_id, ['price' => $this->priceInput]);
        $this->price->load('products');
        $this->priceInput = null;
        $this->nomenclature_id = null;
        $this->dispatchBrowserEvent('set-select2-value', ['id' => 0]);
        $this->alert('success', '', __('prices.prices.added'));
    }
    public function priceValidation($input): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($input, [
            'price' => ['required', 'integer'],
            'nomenclature_id' => ['required', 'integer']
        ]);
    }


    public function deletePrice($id){
        $this->price->products()->detach($id);
        $this->price->load('products');
    }

    public function setPrice($nomenclatureId, $price){
        $this->price->products()->updateExistingPivot($nomenclatureId, [
            'price' => $price
        ]);
    }

}
