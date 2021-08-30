<?php

namespace App\Http\Livewire\Nomenclature;

use App\Traits\CustomLivewireValidate;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class NomenclatureCategoryEdit extends Component
{

    use CustomLivewireValidate;


    public $modals = [
        'addPropertyInCategory' => false,
        'createPropertyInCategory' => false,
    ];

    protected $listeners = [
        'closeAddPropertyModal'
    ];

    public $category;

    public $name;

    public $success;

    public $errors;

    public $parent_id;

    public $categories;

    protected $rules = [
        'name' => 'required',
    ];

    public function mount(\App\Domains\Nomenclature\Models\NomenclatureCategory $category){
        $this->category = $category->load(['parent', 'properties.values']);
        $this->get();
    }


    public function get(){
        $this->name = $this->category->name;
        $this->parent_id = $this->category->parent_id;
    }

    public function set(){
        $this->category->name = $this->name;
        if (!empty($this->parent_id)){
            $this->category->parent_id = $this->parent_id;
        }
    }

    public function render()
    {
        if ($this->category->id){
            $this->categories = \App\Domains\Nomenclature\Models\NomenclatureCategory::where('id', '!=', $this->category->id)->get()->toArray();
        }else{
            $this->categories = \App\Domains\Nomenclature\Models\NomenclatureCategory::all()->toArray();
        }
        return view('livewire.nomenclature.nomenclature-category-edit');
    }

    public function update(){
        $this->nullableResponse();
        $this->set();
        if ($this->validate()->fails())
            return false;
        $this->category->save();
        $this->alert('success', '',  __('nomenclatures.group_success_save'));
        if ($this->category->wasRecentlyCreated){
            return $this->redirect(route('nomenclatures.index'));
        }
    }

    public function nullableResponse(){
        $this->success = null;
        $this->errors = [];
    }

    public function closeAddPropertyModal(){
        $this->modals['addPropertyInCategory'] = false;
    }

}
