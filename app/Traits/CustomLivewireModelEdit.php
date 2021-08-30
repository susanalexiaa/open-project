<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait CustomLivewireModelEdit
{
    public $model;

    public function getProps(){
        foreach ($this->props as $prop) {
            $this->$prop = $this->model->$prop;
        }
    }

    public function setProps(){
        foreach ($this->props as $prop) {
            $this->model->$prop = $this->$prop;
        }
    }

}
