<?php

namespace App\Http\Livewire\Vendor;

use Livewire\Component;

class HierarchicalItem extends Component
{
    public $children = [];
    public $item;
    public $storageName;
    public $elementName;
    public $childrenOpen = false;
    public $hasChild;

    public function render()
    {
        $this->hasChild = $this->item->loadCount('children')->children_count;
        return view('livewire.vendor.hierarchical-item');
    }

    public function getChild(){
        if($this->childrenOpen){
            $this->childrenOpen = false;
        }else{
            $this->item->load('children');
            $this->children = $this->item->children;
            $this->childrenOpen = true;
        }
    }
}
