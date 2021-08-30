<?php

namespace App\Http\Livewire\Nomenclature;

use Livewire\Component;

class NomenclatureIndexCategory extends Component
{

    protected $listeners = ['setActiveCategoryFromIndex'];

    public $activeCategory;

    public $modalNewOneVisible;

    public $category;

    public $children;

    public $hasChild;

    public $childrenOpen = false;

    public function render()
    {
        $this->getChild();
        return view('livewire.nomenclature.nomenclature-index-category');
    }

    public function setActiveCategory(){
        $this->activeCategory = $this->category['id'];
        $this->emit('setActiveCategory', $this->category['id']);
    }

    public function setActiveCategoryFromIndex($id){
//        dd(123);
        $this->activeCategory = $id;
    }

    public function getChild(){
        $this->hasChild = $this->category->load('children')->children->count();
        $this->children = $this->category->children;
    }

    public function openChild(){
        $this->childrenOpen = !$this->childrenOpen;
    }
}
