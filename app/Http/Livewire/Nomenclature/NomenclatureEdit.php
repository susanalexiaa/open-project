<?php

namespace App\Http\Livewire\Nomenclature;

use App\Domains\Directory\Models\MeasureUnit;
use App\Domains\Directory\Models\VatRate;
use App\Domains\Nomenclature\Actions\GenerateProductsAction;
use App\Domains\Nomenclature\Models\NomenclatureProduct;
use App\Domains\Nomenclature\Models\NomenclaturePropertyValue;
use App\Traits\CustomLivewireValidate;
use App\Traits\LivewireMassActions;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use App\Domains\Nomenclature\Models\NomenclatureUser;

class NomenclatureEdit extends Component
{
    use WithFileUploads, CustomLivewireValidate, LivewireMassActions, WithPagination;

    public $nomenclature, $name, $success, $errors;

    /**
     * @var string активная вкладка и поиск по продуктам
     */
    public $activeTab = 'main', $search, $filters = [];

    /**
     * основные поля номенклатуры
     */
    public $article, $code, $barcode, $description = [], $file, $measureUnit = 1, $vatRate = 1, $vatRates, $measureUnits, $category, $extract_to_mobile, $access = [];

    /**
     * @var NomenclatureProduct
     */
    public $propModel;

    public $propArr = ['name' => null, 'sort' => 'null', 'export' => null];

    public $modals = [
        'createProperty' => false,
        'generateProperty' => false,
        'addFilter' => false,
    ];

    public $generatingProps = [];

    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
    ];

    protected function validationAttributes()
    {
        return [
            'barcode' => __('nomenclatures.barcode'),
            'article' => __('nomenclatures.article'),
            'code' => __('nomenclatures.code'),
            'name' => __('nomenclatures.name'),
        ];
    }


    public function mount(\App\Domains\Nomenclature\Models\Nomenclature $nomenclature){
        $users = Auth::user()->currentTeam->allUsers();

        $this->nomenclature = $nomenclature;
        if( $this->nomenclature->exists ){
            $usersWithAccess = $this->nomenclature->users;
            foreach ( $users as $user ){
                $this->access[$user->id] = $usersWithAccess->contains($user->id);
            }
        }else{
            $this->access = array_fill_keys($users->pluck('id')->toArray(), true);
        }
        $this->get();
        $this->setFilters();
    }

    protected function setFilters(){
        $properties = $this->getProperties();
        foreach ($properties as $property) {
            $this->filters[$property->id] = [
                'values' => [],
                'text' => '',
                'name' => $property->name
            ];
        }
    }


    public function get(){
        $this->name = $this->nomenclature->name;
        $this->article = $this->nomenclature->article;
        $this->code = $this->nomenclature->code;
        $this->barcode = $this->nomenclature->barcode;
        $this->category = $this->nomenclature->categories->pluck('id')->toArray();
        $this->name = $this->nomenclature->name;
        $this->article = $this->nomenclature->article;
        $this->vatRate = $this->nomenclature->vat_rate_id ?? 1;
        $this->measureUnit = $this->nomenclature->measure_unit_id ?? 1;
        $this->extract_to_mobile = $this->nomenclature->extract_to_mobile ?? false;
    }


    public function set(){
        $this->nomenclature->name = $this->name;
        $this->nomenclature->article = $this->article;
        $this->nomenclature->code = $this->code;
        $this->nomenclature->barcode = $this->barcode;
        $this->nomenclature->name = $this->name;
        if (isset($this->description['content'])){
            $this->nomenclature['nomenclature-trixFields'] = ['description' => $this->description['content']];
            $this->nomenclature['attachment-nomenclature-trixFields'] = ['description' => $this->description['attachment']];
        }
        $this->nomenclature->article = $this->article;
        $this->nomenclature->measure_unit_id = $this->measureUnit ?? 1;
        $this->nomenclature->vat_rate_id = $this->vatRate ?? 1;
        $this->nomenclature->extract_to_mobile = $this->extract_to_mobile;
    }

    public function render()
    {
        $this->getDirectories();
        return view('livewire.nomenclature.nomenclature-edit', [
            'products' => $this->getProducts()
        ]);
    }

    protected function getProducts(){
        $query = $this->nomenclature->products();
        if (!empty($this->search)){
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        $values = collect($this->filters)->pluck('values')->collapse();
        if ($values->isNotEmpty()){
            $query->whereHas('values', function ($query) use ($values){
                $query->whereIn('nomenclature_property_values.id', $values);
            });
        }
        return $query->with('values')->paginate(10);
    }

    public function update(){
        $this->nullableResponse();
        $this->set();
        if ($this->validate()->fails())
            return false;
        $this->nomenclature->save();
        $this->setRelations();
        $this->alert('success', '', __('nomenclatures.success_save'));
    }

    public function nullableResponse(){
        $this->success = null;
        $this->errors = [];
    }

    public function getDirectories(){
        $this->measureUnits = MeasureUnit::all();
        $this->vatRates = VatRate::all();
    }

    public function setRelations(){
        $categories = \App\Domains\Nomenclature\Models\NomenclatureCategory::getPathCategories($this->category);
        $this->nomenclature->categories()->sync($categories);
        if (!empty($this->file)){
            $this->uploadFile();
        }

        $this->setRelationAccess();
    }

    protected function setRelationAccess(){
        $this->nomenclature->users()->sync(array_keys($this->access, true));
    }

    public function uploadFile(){
        $this->nomenclature->clearMediaCollection('image');
        $this->nomenclature->addMediaFromDisk($this->file->store('photos'))->toMediaCollection('image');
        $this->file = null;
    }

    public function deletePhoto(){
        $this->nomenclature->clearMediaCollection('image');
    }

    public function createProduct(){
        $this->modals['createProperty'] = true;
        $this->propModel = new NomenclatureProduct();
        $this->getProduct();
    }

    public function editProduct($id){
        $this->modals['createProperty'] = true;
        $this->propModel = NomenclatureProduct::query()->findOrFail($id)->load('values');
        $this->getProduct();
    }

    protected function getProduct(){
        //очистка selectable
        $this->clearSelect2Product();
        $this->propArr['name'] = $this->propModel->name;
        $this->propArr['sort'] = $this->propModel->sort;
        $this->propArr['export'] = $this->propModel->export;
        foreach ($this->propModel->values as $value) {
            $event = 'set-property-' . $value->property_id . '-value';
            $this->dispatchBrowserEvent($event, ['id' => $value->id, 'text' => $value->name]);

        }
    }

    public function saveProduct(){
        $validator = Validator::make($this->propArr, [
            'name' => 'required',
            'sort' => 'integer'
        ]);
        if ($validator->fails()){
            foreach ($validator->errors()->getMessages() as $code => $message) {
                $this->addError($code, $message);
            }
            return false;
        }
        $this->propModel->name = $this->propArr['name'];
        $this->propModel->sort = $this->propArr['sort'];
        $this->propModel->export = (!empty($this->propArr['export']) ? $this->propArr['export'] : false);
        $this->propModel->nomenclature_id = $this->nomenclature->id;
        $this->propModel->save();
        if (isset($this->propArr['props']) AND (!empty($this->propArr['props']))) {
            $this->propModel->values()->sync(array_values($this->propArr['props']));
        }
        $this->alert('success', '', 'Успешно сохранено!');
        $this->modals['createProperty'] = false;
//        $this->nomenclature->load('products');
    }

    public function changeMassSelect($value){
        switch ($value){
            case 'delete':
                if ($this->forAllCheckbox){
                    $this->nomenclature->products()->delete();
                }else{
                    $this->nomenclature->products()->whereIn('id', $this->mass)->delete();
                }

                break;
        }
//        $this->nomenclature->load('products');
    }

    public function updatedSelectAllProperties()
    {
        if ($this->selectAllProperties){
            $this->mass = $this->getProducts()->pluck('id');
        }else{
            $this->mass = [];
        }
    }

    public function getProperties(){
        return $this->nomenclature->categories->pluck('properties')->collapse()->unique('id');
    }

    public function generateProducts(){
        $this->generatingProps = [];
        $this->clearSelect2GenerateProperties();
        foreach ($this->getProperties() as $property) {
            $this->generatingProps[] = [
                'id' => $property->id,
                'name' => $property->name,
                'condition' => 1,
                'values' => []
            ];
        }
        $this->modals['generateProperty'] = true;
    }

    public function startGenerateProducts(){
        if (collect($this->generatingProps)->pluck('values')->collapse()->isEmpty())
            return $this->addError('generate', 'Выберите свойства для генерации!');
        $action = new GenerateProductsAction($this->nomenclature);
        if ($action->execute($this->generatingProps)){
//            $this->nomenclature->load('products');
//            dd($this->nomenclature);
            $this->alert('success', '', 'Характеристики номенклатуры успешно сгенерированы!');
            $this->modals['generateProperty'] = false;
        }
    }

    protected function clearSelect2Product(){
        $selectIds = $this->getProperties()->pluck('id');
        foreach ($selectIds as $selectId) {
            $event = 'set-property-' . $selectId . '-value';
            $this->dispatchBrowserEvent($event, ['id' => 0]);
        }
    }

    protected function clearSelect2GenerateProperties(){
        $selectIds = $this->getProperties()->pluck('id');
        foreach ($selectIds as $selectId) {
            $event = 'set-generate-property-' . $selectId . '-value';
            $this->dispatchBrowserEvent($event, ['id' => 0]);
        }
    }

    public function resetFilter(){
        $this->search = null;
    }

    public function addFilter($id, $values){
        $names = NomenclaturePropertyValue::query()->findOrFail($values)->pluck('name');
        $this->filters[$id]['text'] = $names->implode(', ');
        $this->filters[$id]['values'] = $values;
    }

    public function deleteFilter($id){
        $this->filters[$id]['values'] = [];
        $this->dispatchBrowserEvent('set-filter-property-' . $id .'-value', ['id' => 0]);
    }

    public function selectAllPropertiesInGenerate($propertyId, $eventName){
        $values = NomenclaturePropertyValue::query()->where('property_id', $propertyId)->select(['id', 'name'])->orderBy('sort')->get();
        $values = $values->pluck('name', 'id');
        $arr = [];
        foreach ($values as $id => $value) {
            $arr[] = ['id' => $id, 'text' => $value];
        }
        $this->dispatchBrowserEvent($eventName, $arr);
    }

    public function clearPropertiesInGenerate($eventName){
        $this->dispatchBrowserEvent($eventName, ['id' => 0]);
    }

    public function saveAccess()
    {
        $this->setRelationAccess();
        $this->alert('success', '', __('nomenclatures.access_success_save'));
    }
}
