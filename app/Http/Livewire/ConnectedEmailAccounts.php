<?php

namespace App\Http\Livewire;

use App\Domains\Directory\Models\Operator;
use App\Domains\Integration\Models\ConnectedEmailAccount;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ConnectedEmailAccounts extends Component
{
    public $showCreatePopup = false;
    public $confirmingDeletion = false;

    public $modalId;
    public $operators;

    public $name;
    public $operator_id;
    public $login;
    public $password;
    public $last_integration;
    public $is_active;


    public function render()
    {
        return view('livewire.connected-email-accounts', [
            'accounts' => Auth::user()->emailAccounts
        ]);
    }

    public function mount()
    {
        $this->operators = Operator::all();
    }

    public function showCreatePopup()
    {
        $this->cleanVar();
        $this->showCreatePopup = true;
    }

    public function modelData()
    {
        $data = [
            'name' => $this->name,
            'operator_id' => $this->operator_id,
            'login' => $this->login,
            'password' => $this->password,
            'is_active' => $this->is_active,
            'user_id' => Auth::id()
        ];

        return $data;
    }

    public function cleanVar()
    {
        $this->modalId = null;

        $this->name = '';
        $this->operator_id = $this->operators[0]->id;
        $this->login = '';
        $this->password = '';
        $this->allowed_addresses = '';
        $this->last_integration = null;
        $this->is_active = false;
    }

    public function create()
    {
        $account = ConnectedEmailAccount::create( $this->modelData() );
        $account->setIntegrationTime( $this->last_integration );

        $this->showCreatePopup = false;
    }

    public function updateShowModal($id)
    {
        $this->cleanVar();
        $this->resetValidation();
        $this->modalId = $id;
        $this->showCreatePopup = true;

        $this->loadData();
    }

    private function loadData()
    {
        $data = ConnectedEmailAccount::find($this->modalId);

        $this->name = $data->name;
        $this->operator_id = $data->operator_id;
        $this->login = $data->login;
        $this->password = $data->password;
        $this->last_integration = $data->getIntegrationLocalTime();
        $this->is_active = $data->is_active;
    }

    public function update()
    {
        $data = ConnectedEmailAccount::find($this->modalId);
        $data->update( $this->modelData() );
        $data->setIntegrationTime( $this->last_integration );
        $this->showCreatePopup = false;
    }

    public function deleteShowModal($id)
    {
        $this->cleanVar();
        $this->resetValidation();
        $this->modalId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete()
    {
        $data = ConnectedEmailAccount::find($this->modalId);
        $data->delete();
        $this->confirmingDeletion = false;
    }
}
