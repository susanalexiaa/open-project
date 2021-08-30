<?php

namespace App\Http\Livewire\Appointments;

use App\Domains\Appointment\Models\Appointment;
use App\Traits\CustomLivewireModelEdit;
use App\Traits\CustomLivewireValidate;
use Carbon\Carbon;
use App\Domains\Appointment\Actions\RemoveAppointmentImageAction;
use Illuminate\Http\Request;
use Livewire\Component;

class AppointmentEdit extends Component
{

    use CustomLivewireModelEdit, CustomLivewireValidate;

    public $title, $objective = 'Обмен', $customer_id, $individual_id, $comment = [], $hours = 8, $minutes = 0;

    public $date;

    protected $props = [
        'title',
    ];

    protected $rules = [
        'title' => 'required',
    ];

    protected function validationAttributes(): array
    {
        return [
            'title' => "Резюме",
            'objective' => "Цель",
            'individual_id' => "Контакт",
            'customer_id' => "Контрагент"
        ];
    }

    public function mount(Appointment $model, Carbon $date){
        $this->model = $model;
        $this->date = $date;
        $this->getProps();
        if ($this->model->exists){
            $this->hours = $this->model->hours;
            $this->minutes = $this->model->minutes;
        }
    }

    public function render()
    {
        return view('livewire.appointments.appointment-edit');
    }


    public function update(){
        if ($this->validate()->fails())
            return false;
        $this->setProps();
        if (!$this->model->exists){
            $this->model->datetime = $this->date->setTime((int)$this->hours, (int)$this->minutes);
            $this->model->user_id = \Auth::id();
            $this->model->team_id = \Auth::user()->personalTeam()->id;
        }else{
            $this->model->datetime = $this->model->datetime->setTime((int)$this->hours, (int)$this->minutes);
        }
        if (!empty($this->comment['content'])){
            $this->model['appointment-trixFields'] = [
                'content' => base64_decode($this->comment['content'])
            ];
            $this->model['attachment-appointment-trixFields'] = [
                'content' => base64_decode($this->comment['attachment'])
            ];
        }
        $this->model->save();
        return redirect(route('appointments.list'));
    }

    public function deleteImage($image){
        $this->model->deleteMedia($image);
        $this->model->load('media');
    }
}
