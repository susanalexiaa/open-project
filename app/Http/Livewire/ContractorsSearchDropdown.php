<?php

namespace App\Http\Livewire;

use App\Domains\Contractor\Models\Contractor;
use Livewire\Component;
use Illuminate\Support\Collection;
use Asantibanez\LivewireSelect\LivewireSelect;

class ContractorsSearchDropdown extends LivewireSelect
{

    public function options($searchTerm = null) : Collection
    {
        $result = [];
        $contractors = Contractor::where('title', 'LIKE', '%'. $searchTerm .'%')
                                ->orWhere('phone', 'LIKE', '%'. $searchTerm .'%')->limit(5)->get();

        foreach( $contractors as $contractor ){
            $result[] = [
                'value' => $contractor->id,
                'description' => $contractor->title.'. '.$contractor->getFormattedPhone(),
            ];
        }

        return collect($result);
    }

    public function selectedOption($value)
    {
        $contractor = Contractor::find($value);

        $this->emit('contractorHasBeenChosen', $value);

        return [
            'value' => $contractor->id,
            'description' => $contractor->title,
        ];
    }

    public function contractorHasBeenSaved()
    {
        $this->searchTerm = '';
        $this->value = null;
    }

    public function getListeners()
    {
        $result = collect($this->dependsOn)
            ->mapWithKeys(function ($key) {
                return ["{$key}Updated" => 'updateDependingValue'];
            })
            ->toArray();

        $result['contractorHasBeenSaved'] = 'contractorHasBeenSaved';
        return $result;
    }
}
