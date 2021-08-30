<?php

namespace App\Http\Livewire\Admin;

use App\Domains\Directory\Models\Operator;
use Livewire\Component;
use Livewire\WithPagination;
use App\Domains\Integration\Models\TelephonyIntegration as Integration;

/**
 * Class Integrations
 * @package App\Http\Livewire
 */
class TelephonyIntegration extends Component
{
    use WithPagination;

    public $modalFormVisible = false;
    public $confirmingDeletion = false;

    public $operators;

    public $modalId;

    public $name;
    public $type;
    public $key;
    public $secret;
    public $last_integration;
    public $is_active;
    public $team_id;
    public $responsible_id;

    protected $listeners = ['teamForLeadHasBeenChoosen', 'responsibleUserHasBeenChosen'];

    public function create()
    {

        if( is_null($this->team_id) ){
            $this->addError('team_id', 'Team is required');
            return 1;
        }

        $integration = Integration::create( $this->modelData() );
        $integration->setIntegrationTime( $this->last_integration );

        $this->cleanVar();
        $this->modalFormVisible = false;
    }

    public function update()
    {
        $integration = Integration::find($this->modalId);
        $integration->update( $this->modelData() );
        $integration->setIntegrationTime( $this->last_integration );
        $this->modalFormVisible = false;
    }

    public function delete()
    {
        $integration = Integration::find($this->modalId);
        $integration->delete();
        $this->confirmingDeletion = false;
    }

    public function mount()
    {
        $this->resetPage();
        $this->operators = Operator::all();
    }

    public function modelData()
    {
        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'key' => $this->key,
            'secret' => $this->secret,
            'is_active' => $this->is_active,
            'team_id' => $this->team_id
        ];

        if( !is_null($this->responsible_id) ){
            $data['responsible_id'] = $this->responsible_id;
        }

        return $data;
    }

    public function cleanVar()
    {
        $this->modalId = null;

        $this->name = '';
        $this->type = 'Zadarma';
        $this->key = '';
        $this->secret = '';
        $this->last_integration = null;
        $this->is_active = false;
        $this->team_id = null;
        $this->responsible_id = null;
    }

    public function createShowModal()
    {
        $this->resetValidation();
        $this->cleanVar();
        $this->modalFormVisible = true;
    }

    public function updateShowModal($id)
    {
        $this->cleanVar();
        $this->resetValidation();
        $this->modalId = $id;
        $this->modalFormVisible = true;

        $this->loadData();
    }

    public function deleteShowModal($id)
    {
        $this->cleanVar();
        $this->resetValidation();
        $this->modalId = $id;
        $this->confirmingDeletion = true;
    }

    public function read()
    {
        return Integration::paginate(5);
    }

    public function render()
    {
        return view('livewire.admin.telephony-integration', [
            "data" => $this->read(),
        ]);
    }

    private function loadData()
    {
        $data = Integration::find($this->modalId);

        $this->name = $data->name;
        $this->type = $data->type;
        $this->key = $data->key;
        $this->secret = $data->secret;
        $this->last_integration = $data->getIntegrationLocalTime();
        $this->is_active = $data->is_active;
        $this->team_id = $data->team_id;
        $this->responsible_id = $data->responsible_id;

        $this->emit('responsibleUserSetNew', $data->responsible_id);
        $this->emit('teamForLeadSetNew', $data->team_id);
    }

    public function teamForLeadHasBeenChoosen($id)
    {
        $this->team_id = $id;
    }

    public function responsibleUserHasBeenChosen($id)
    {
        $this->responsible_id = $id;
    }
}
