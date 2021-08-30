<?php

namespace App\Http\Livewire\Nomenclature\Property;

use App\Domains\Nomenclature\Models\NomenclatureProperty;
use App\Domains\Nomenclature\Models\NomenclaturePropertyValue;
use App\Traits\CustomLivewireValidate;
use App\Traits\LivewireMassActions;
use Livewire\Component;
use Livewire\WithPagination;

class PropertyEdit extends Component
{

    use CustomLivewireValidate, LivewireMassActions, WithPagination;

    protected $listeners = [
        'openPropertyEditModal' => 'init'
    ];

    public $modals = [
        'mainModal' => false,
        'createNewValue' => false,
    ];

    public $propValues = [
        'name' => null,
        'sorting' => null,
    ];

    public $propertyModel;

    protected $rules = [
        'name' => 'required',
        'type' => 'required',
        'sort' => 'required|integer'
    ];

    public $activeTab = 'main';

    public $name;

    public $sort;

    public $type;

    public $model;

    public function mount(NomenclatureProperty $property){
        $this->model = $property->load('values');
        $this->get();
    }

    protected function get(){
        $this->name = $this->model->name;
        $this->sort = $this->model->sort ?? 0;
        $this->type = $this->model->type ?? 1;
    }

    protected function validationAttributes(){
        return [
            'type' => 'Тип',
            'sort' => 'Сортировка'
        ];
    }


    public function render()
    {
        return view('livewire.nomenclature.property.property-edit', [
            'values' => $this->getValues()
        ]);
    }

    public function update(){
        if ($this->validate()->fails()){
            return false;
        }
        $this->set();
        $this->model->save();
        $this->emit('saveProperty', $this->model->id);
        $this->alert('success', '', 'Свойство успешно сохранено!');
    }

    protected function set(){
        $this->model->name = $this->name;
        $this->model->sort = $this->sort;
        $this->model->type = $this->type;
    }

    public function saveProperty(){
        if (empty(trim($this->propValues['name'])))
            return $this->alert('error', '', 'Поле "Название" обязательно к заполнению!');
        if (is_null($this->propertyModel)){
            $this->propertyModel = new NomenclaturePropertyValue();
        }
        $this->propertyModel->name = $this->propValues['name'];
        $this->propertyModel->sort = $this->propValues['sorting'] ?? 0;
        $this->propertyModel->property_id = $this->model->id;
        $this->propertyModel->save();
        $this->model->load('values');
        $this->modals['createNewValue'] = false;
        $this->emit('saveProperty', $this->model->id);
        $this->alert('success', '', 'Значение свойства успешно сохранено!');
    }

    public function editValue($id){
        $this->propertyModel = NomenclaturePropertyValue::query()->findOrFail($id);
        $this->modals['createNewValue'] = true;
        $this->propValues['name'] = $this->propertyModel->name;
        $this->propValues['sorting']  = $this->propertyModel->sort ?? 0;
    }

    public function createNewValue(){
        $this->propertyModel = new NomenclaturePropertyValue();
        $this->modals['createNewValue'] = true;
        $this->propValues['name'] = $this->propertyModel->name;
        $this->propValues['sorting']  = $this->propertyModel->sort ?? 0;
    }

    public function init($id){
        if (!empty($id)){
            $this->model = NomenclatureProperty::query()->findOrFail($id);
        }else{
            $this->model = new NomenclatureProperty();
        }
        $this->get();
        $this->modals['mainModal'] = true;
        $this->activeTab = 'main';
    }

    public function changeMassSelect($value){
        switch ($value){
            case 'delete':
                if ($this->forAllCheckbox){
                    $this->model->values()->delete();
                }else{
                    $this->model->values()->whereIn('id', $this->mass)->delete();
                }

                break;
        }
        $this->model->load('values');
    }

    public function updatedSelectAllProperties()
    {
        if ($this->selectAllProperties){
            $this->mass = $this->model->values->pluck('id');
        }else{
            $this->mass = [];
        }
    }

    public function getValues(){
        return $this->model->values()->paginate(5);
    }
}
