<?php

namespace App\Http\Livewire\Nomenclature;

use App\Domains\Nomenclature\Models\Nomenclature;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class NomenclatureIndex extends Component
{
    use WithPagination;

    public $categories;

    public $modalNewOneVisible;

    public $activeCategory;

    protected $nomenclatures;

    protected $listeners = ['setActiveCategory', 'updateCategoriesList' => 'getCategories'];

    public function render()
    {
        $this->getCategories();
        $this->getNomenclatures();
        return view('livewire.nomenclature.nomenclature-index', [
            'nomenclatures' => $this->nomenclatures
        ]);
    }

    public function setActiveCategory($id){
        $this->activeCategory = $id;
        $this->gotoPage(1);
        $this->emit('setActiveCategoryFromIndex', $this->activeCategory);
    }


    public function getNomenclatures(){
        $query = Nomenclature::query();
        if ($this->activeCategory){
            $activeCategory = $this->activeCategory;
            $query = $query->whereHas('categories', function (Builder $query) use($activeCategory) {
                $query->where('nomenclature_categories.id', $activeCategory);
            });
        }
        $this->nomenclatures = $query->paginate(15);
    }

    public function getCategories(){
        $this->categories = \App\Domains\Nomenclature\Models\NomenclatureCategory::where('parent_id', 0)
            ->with('children')
            ->get();
//        dd(123);
    }
}

