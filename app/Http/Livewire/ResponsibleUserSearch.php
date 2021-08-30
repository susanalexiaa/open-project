<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Asantibanez\LivewireSelect\LivewireSelect;
use App\Models\User;

class ResponsibleUserSearch extends LivewireSelect
{
    
    public function options($searchTerm = null) : Collection
    {
        $result = [];
        $users = User::where('name', 'LIKE', '%'. $searchTerm .'%')->limit(5)->get();

        foreach( $users as $user ){
            $result[] = [
                'value' => $user->id,
                'description' => $user->name,
            ];
        }

        return collect($result);
    }

    public function selectedOption($value)
    {
        $user = User::find($value);

        $this->emit('responsibleUserHasBeenChosen', $value);

        return [
            'value' => $user->id,
            'description' => $user->name,
        ];
    }

    public function responsibleUserHasBeenSaved()
    {
        $this->searchTerm = '';
        $this->value = null;
    }

    public function responsibleUserSetNew($id)
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

        $result['responsibleUserHasBeenSaved'] = 'responsibleUserHasBeenSaved';
        $result['responsibleUserSetNew'] = 'responsibleUserSetNew';

        return $result;
    }
}
