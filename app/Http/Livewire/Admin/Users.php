<?php

namespace App\Http\Livewire\Admin;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\PasswordValidationRules;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class Users
 * @package App\Http\Livewire
 */
class Users extends Component
{
    use WithPagination;
    use PasswordValidationRules;

    public $modalFormVisible = false;
    public $confirmingUserDeletion = false;
    public $modalId;
    public $name;
    public $email;
    public $position;
    public $recording_allowed;
    public $password;
    public $password_confirmation;
    public $team_id;
    public $teams;

    public function create()
    {
        (new CreateNewUser())->create($this->modelData());
        $this->cleanVar();
        $this->modalFormVisible = false;
    }

    public function update()
    {
        $user = User::find($this->modalId);
        (new UpdateUserProfileInformation())->update($user, $this->modelData());
        if ($this->password) {
            (new ResetUserPassword())->reset($user, $this->modelData());
        }
        $this->modalFormVisible = false;
    }

    public function delete()
    {
        $user = User::find($this->modalId);
        (new DeleteUser(new DeleteTeam))->delete($user);
        $this->confirmingUserDeletion = false;
    }

    public function mount()
    {
        $this->teams = Team::query()->get();
        $this->resetPage();
    }

    public function modelData()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'team_id' => $this->team_id,
            'position' => $this->position,
            'recording_allowed' => $this->recording_allowed,
        ];
    }

    public function cleanVar()
    {
        $this->modalId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->position = '';
        $this->recording_allowed = false;
        $this->team_id = $this->teams->first()->id;
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
        $this->confirmingUserDeletion = true;
    }

    public function read()
    {
        return User::paginate(5);
    }

    public function render()
    {
        return view('livewire.admin.users', [
            "data" => $this->read(),
        ]);
    }

    private function loadData()
    {
        $data = User::find($this->modalId);
        $this->name = $data->name;
        $this->email = $data->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->position = $data->position;
        $this->recording_allowed = $data->recording_allowed;
    }
}
