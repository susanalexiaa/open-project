<?php

namespace App\Traits;


trait LivewireMassActions
{
    public $mass = [];

    public $forAllCheckbox = false;

    public $selectAllProperties;

    abstract function updatedSelectAllProperties();

    abstract function changeMassSelect($value);
}
