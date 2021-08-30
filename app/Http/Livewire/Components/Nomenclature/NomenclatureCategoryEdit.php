<?php

namespace App\Http\Livewire\Components\Nomenclature;

use App\Domains\Nomenclature\Models\NomenclatureCategory;
use App\Traits\CustomLivewireValidate;
use App\Traits\LivewireMassActions;
use Livewire\Component;
use Livewire\WithPagination;

class NomenclatureCategoryEdit extends Component
{
    use CustomLivewireValidate, LivewireMassActions, WithPagination;

    protected $listeners = [
        'openCategoryEditModal',
        'closeAddPropertyModal',
        'saveProperty',
        'addedPropertyInCategory'
    ];

    public $modals = [
        'mainModal' => false,
        'addPropertyInCategory' => false,
        'createPropertyInCategory' => false,
    ];

    public $activeTab = 'main';

    public $category;

    public $name;

    public $success;

    public $parent_id;



    protected $rules = [
        'name' => 'required',
    ];

    public function mount(){
        $this->category = new NomenclatureCategory();
    }

    public function get(){
        $this->name = $this->category->name;
        $this->parent_id = $this->category->parent_id;
        if (!empty($this->category->parent_id)){
            $this->dispatchBrowserEvent('set-category-value' , ['id' => $this->category->parent_id, 'text' => $this->category->parent->name]);
        }else{
            $this->dispatchBrowserEvent('set-category-value' , ['id' => 0]);
        }
    }

    public function set(){
        $this->category->name = $this->name;
        if (!empty($this->parent_id)){
            $this->category->parent_id = $this->parent_id;
        }
    }

    public function render()
    {
        $properties = $this->getPropertiesList();
        return view('livewire.components.nomenclature.nomenclature-category-edit', compact('properties'));
    }

    public function update(){
        $this->set();
        if ($this->validate()->fails())
            return false;
        $this->category->save();
        $this->alert('success', '',  __('nomenclatures.group_success_save'));
        $this->emit('updateCategoriesList');
    }

    public function openCategoryEditModal($id){
        if (empty($id)){
            $this->category = new NomenclatureCategory();
        }else{
            $this->category = NomenclatureCategory::query()->findOrFail($id);
        }
        $this->modals['mainModal'] = true;
        $this->activeTab = 'main';
        $this->get();
    }

    public function closeAddPropertyModal(){
        $this->modals['addPropertyInCategory'] = false;
    }

    public function saveProperty($id){
        $this->category->properties()->syncWithoutDetaching($id);
        $this->category->load('properties');
    }

    public function addedPropertyInCategory(){
        $this->category->load('properties');
    }

    public function updatedSelectAllProperties(){
        if ($this->selectAllProperties){
            $this->mass = $this->category->properties->pluck('id');
            foreach ($this->mass as $mass) {
                $arr = (string)$mass;
            }
            $this->mass = $arr;
        }else{
            $this->mass = [];
        }
    }

    public function changeMassSelect($value){
        switch ($value){
            case 'detach':
                if ($this->forAllCheckbox){
                    $this->category->properties()->sync([]);
                }else{
                    $this->category->properties()->detach($this->mass);
                }
                break;
        }
        $this->category->load('properties');
    }

    public function getPropertiesList(){
        return $this->category->properties()->paginate(5);
    }
}
