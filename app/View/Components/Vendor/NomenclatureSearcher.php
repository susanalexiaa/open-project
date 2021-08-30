<?php

namespace App\View\Components\Vendor;

use Illuminate\View\Component;

class NomenclatureSearcher extends Component
{


    public $outputEvent;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->outputEvent = $outputEvent;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.vendor.nomenclature-searcher');
    }
}
