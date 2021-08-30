<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Asantibanez\LivewireSelect\LivewireSelect;
use App\Models\Team;

class TeamSearch extends LivewireSelect
{
    
    public function options($searchTerm = null) : Collection
    {
        $result = [];
        $teams = Team::where('name', 'LIKE', '%'. $searchTerm .'%')->limit(5)->get();

        foreach( $teams as $team ){
            $result[] = [
                'value' => $team->id,
                'description' => $team->name,
            ];
        }

        return collect($result);
    }

    public function selectedOption($value)
    {
        $team = Team::find($value);

        $this->emit('teamForLeadHasBeenChoosen', $value);

        return [
            'value' => $team->id,
            'description' => $team->name,
        ];
    }

    public function teamForLeadHasBeenSaved()
    {
        $this->searchTerm = '';
        $this->value = null;
    }

    public function teamForLeadSetNew($id)
    {
        $this->searchTerm = '';
        $this->value = $id;
    }

    public function getListeners()
    {
        $result = collect($this->dependsOn)
            ->mapWithKeys(function ($key) {
                return ["{$key}Updated" => 'updateDependingValue'];
            })
            ->toArray();

        $result['teamForLeadHasBeenSaved'] = 'teamForLeadHasBeenSaved';
        $result['teamForLeadSetNew'] = 'teamForLeadSetNew';

        return $result;
    }
}
