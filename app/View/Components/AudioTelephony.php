<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AudioTelephony extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $model;
    public $showContractor;

    public function __construct($model, $showContractor = false)
    {
        $this->model = $model;
        $this->showContractor = $showContractor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.audio-telephony');
    }
}
