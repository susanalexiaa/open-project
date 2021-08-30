<?php

namespace App\Http\Livewire\Nomenclature\Property;

use App\Domains\Nomenclature\Models\NomenclatureCategory;
use App\Domains\Nomenclature\Models\NomenclatureProperty;
use App\Traits\CustomLivewireValidate;
use App\Traits\LivewireMassActions;
use Livewire\Component;
use Livewire\WithPagination;

class AddPropertyInCategory extends Component
{

    use CustomLivewireValidate, LivewireMassActions, WithPagination;

    public $modals = [
        'mainModal' => false,
    ];

    protected $listeners = [
        'openAddPropertyInCategory' => 'init'
    ];

    public $category;

    public $search;

    public $checkboxes = [];

    public function render()
    {
        return view('livewire.nomenclature.property.add-property-in-category', [
            'properties' => $this->getList()
        ]);
    }


    public function init($id){
        $this->category = NomenclatureCategory::query()->findOrFail($id);
        $this->modals['mainModal'] = true;
        $this->search = null;
        $this->checkboxes = [];
    }
    protected function getList(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = NomenclatureProperty::query();
        if (!empty($this->search)){
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        return $query->paginate(5);
    }

    public function resetFilter(){
        $this->search = null;
    }

    public function addProperties(){
        if (empty($this->checkboxes)){
            return $this->alert('error', '', 'Вы не выбрали ни одно из свойств!');
        }
        $this->category->properties()->syncWithoutDetaching($this->checkboxes);
        $this->alert('success', '', 'Свойства успешно добавлены в категорию!');
        $this->modals['mainModal'] = false;
        $this->emit('addedPropertyInCategory');
    }

    public function updatedSearch(){
        $this->checkboxes = [];
    }
    public function changeMassSelect($value){
    }

    public function updatedSelectAllProperties()
    {
        if ($this->selectAllProperties){
            $this->checkboxes = $this->getList()->pluck('id');
        }else{
            $this->checkboxes = [];
        }
    }
}
