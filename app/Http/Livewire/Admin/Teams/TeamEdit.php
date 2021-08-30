<?php

namespace App\Http\Livewire\Admin\Teams;

use App\Models\Team;
use App\Traits\CustomLivewireValidate;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class TeamEdit extends Component
{

    use CustomLivewireValidate;

    public $name;
    public $users;
    public $address;
    public $locality_id;
    public $coords;
    public $work_starts_at;
    public $work_ends_at;
    public $team;
    public $success;
    public $errors;


    protected $rules = [
        'name' => 'required',
        'work_starts_at' => 'required',
        'work_ends_at' => 'required'
    ];

    public function mount(Team $team){
        $this->team = $team;
        $this->get();
    }

    protected function validationAttributes(){
        return [
            'work_starts_at' => __('teams.work_starts_at'),
            'work_ends_at' => __('teams.work_ends_at')
        ];
    }

    public function render()
    {
        return view('livewire.admin.teams.team-edit');
    }

    public function get(){
        $this->name = $this->team->name;
        $this->address = $this->team->address;
        $this->locality_id = $this->team->locality_id;
        $this->coords = $this->team->coords;
        $this->work_starts_at = $this->team->work_starts_at;
        $this->work_ends_at = $this->team->work_ends_at;

    }

    public function set(){
        $this->team->name = $this->name;
        $this->team->address = $this->address;
        $this->team->locality_id = $this->locality_id;

        $this->team->coords = $this->coords;
        $this->team->work_starts_at = $this->work_starts_at;
        $this->team->work_ends_at = $this->work_ends_at;
    }

    public function updateTeam(){
        if ($this->validate()->fails())
            return false;
        $this->set();
        if (!$this->team->exists){
            $this->team->user_id = \Auth::id();
            $team = Team::where('name', $this->name)->first();
            if (!empty($team)){
                return $this->addError('name', 'Группа с таким названием уже существует!');
            }
        }
        $this->team->save();
        $this->alert('success', '', __('teams.success_save'));
        return true;
    }


}
