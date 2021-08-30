<?php

namespace App\View\Components\Vendor;

use Illuminate\View\Component;

class Select2 extends Component
{
    public $url;
    public $placeholder;
    public $name;
    public $id;

    /**
     * Create a new component instance.
     *
     * @param $url
     * @param $number
     */
    public function __construct($url, $id, $placeholder)
    {
        $this->id = $id;
        $this->url = $url;
        $this->placeholder = $placeholder;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.vendor.select2');
    }
}
