<?php

namespace App\Http\Livewire\Employees\Schedule;

use App\Domains\Employees\Models\EmployeesSchedule;
use App\Domains\Employees\Models\WorkingCell;
use App\Domains\Employees\Models\WorkingDay;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $title;
    public $year;
    public $employees;
    public $schedule_model;
    public $schedule_data;

    public $showModalAutocomplite = false;
    public $employee_id = 0;
    public $date_start;
    public $days = [0];

    public $work_starts_at;
    public $work_ends_at;
    public $choosed_employee;
    public $choosed_date;
    public $hours_per_day;
    public $base_hours_per_day;
    public $busy_template = [];
    public $busy = [];
    public $showModalBusy = false;

    public $team_id;


    public function mount()
    {
        $team = Auth::user()->currentTeam;
        $this->team_id = $team->id;

        $this->year = $this->schedule_model->year ?? date("Y");
        $this->title = $this->schedule_model->name ?? "График работы";
        $this->employees = $team->allUsers();
        $this->work_starts_at = $team->work_starts_at;
        $this->work_ends_at = $team->work_ends_at;
        $this->schedule_data = $this->getScheduleData();
        $this->checkTheScheduleAvailabilityByYear();
    }

    protected function getScheduleData()
    {
        $data = [];
        $employees = $this->employees;

        for ($i = 0; $i < 12; $i++) {
            $dataSet = [];

            foreach ($employees as $employee) {
                $dates = [];

                for ($j = 0; $j < 31; $j++) {
                    $month = $i + 1;
                    $day = $j + 1;

                    $key = "{$this->year}-{$month}-{$day}";

                    if (!checkdate($month, $day, $this->year)) {
                        $dates[$key] = null;
                        continue;
                    }

                    if (!is_null($this->schedule_model)) {

                        $day = WorkingDay::where([
                            'schedule_id' => $this->schedule_model->id,
                            'user_id' => $employee->id,
                            'day' => Carbon::parse($key)
                        ])->firstOrFail();

                        $dates[$key] = $day->hours;
                    } else {
                        $dates[$key] = 0;
                    }
                }

                $dataSet[$employee->id] = $dates;
            }

            $data[] = $dataSet;
        }

        return $data;
    }

    public function checkTheScheduleAvailabilityByYear()
    {
        if (is_null($this->schedule_model)) {
            return $this->validate(
                ['year' => 'unique:employees_schedules']
            );
        }
    }

    public function render()
    {
        return view('livewire.employees.schedule.create');
    }

    public function addDay()
    {
        $this->days[] = 0;
    }

    public function changeYear()
    {
        $this->checkTheScheduleAvailabilityByYear();
        $this->schedule_data = $this->getScheduleData();
    }

    public function createAutoComplete()
    {
        $this->validate(
            ['date_start' => 'required', 'employee_id' => 'not_in:0', 'days' => 'min:1'],
            [
                'days.min' => 'Периодичность рабочих дней не задана'
            ],
            [
                'date_start' => 'дата отсчета',
                'employee_id' => 'сотрудник'
            ]
        );

        $date_start = Carbon::parse($this->date_start);
        if ($date_start->year != $this->year) {
            return $this->addError('date', 'Год графика и дата отсчета не совпадает');
        }

        $schedule = $this->schedule_data;

        $count = 0;
        foreach ($schedule as $key_month => &$month) {
            if ($key_month < $date_start->month - 1) continue;

            $employee = &$month[$this->employee_id];
            foreach ($employee as $date => &$day) {
                if (is_null($day)) continue;

                if (Carbon::parse($date)->gte($date_start)) {
                    $day = $this->days[$count % count($this->days)];
                    ++$count;
                }
            }
        }

        $this->schedule_data = $schedule;
        $this->hideAndClearAutocomplete();
    }

    public function hideAndClearAutocomplete()
    {
        $this->showModalAutocomplite = false;
    }

    public function openAutocomplete()
    {
        $this->days = [0];
        $this->employee_id = 0;
        $this->date_start = null;
        $this->showModalAutocomplite = true;
    }

    public function openModalBusy($employee_id, $cell_date, $hours)
    {
        if ($hours == 0) {
            return;
        }

        $this->choosed_employee = User::findOrFail($employee_id);
        $this->choosed_date = Carbon::parse($cell_date);

        $this->busy_template = $this->getBusy();
        $this->base_hours_per_day = $hours;
        $this->hours_per_day = max($hours, $this->getCurrentHoursBasedOnBusy());

        $this->showModalBusy = true;
    }

    protected function getBusy()
    {
        $busy = [];
        $employee_id = $this->choosed_employee->id;

        $date_process = (clone $this->choosed_date)->add($this->work_starts_at, 'hour');
        $date_end = (clone $this->choosed_date)->add($this->work_ends_at, 'hour');

        while ($date_end->gt($date_process)) {
            $key = $date_process->format('Y-m-d H:i');

            if (isset($this->busy[$employee_id][$key])) {
                $busy[$key] = $this->busy[$employee_id][$key];
            } else if (isset($this->schedule_model)) {

                $day = WorkingDay::where([
                    'schedule_id' => $this->schedule_model->id,
                    'user_id' => $employee_id,
                    'day' => (clone $date_process)->startOfDay()
                ])->firstOrFail();

                $cell = WorkingCell::where([
                    'day_id' => $day->id,
                    'start_period' => $date_process
                ])->first();

                $busy[$key] = $cell ? $cell->is_busy : 0;
            } else {
                $busy[$key] = 0;
            }

            $date_process = $date_process->add(15, 'minute');
        }

        return $busy;
    }

    protected function getCurrentHoursBasedOnBusy()
    {
        $cout_of_parts = 0;

        foreach ($this->busy_template as $busy) {
            if ($busy) $cout_of_parts++;
        }

        return $cout_of_parts * 15 / 60;
    }

    public function saveSchedule()
    {
        $this->checkTheScheduleAvailabilityByYear();
        $is_new = is_null($this->schedule_model);

        if (!$is_new) {
            $schedule = $this->schedule_model;
            $schedule->update(['name' => $this->title]);
        } else {
            $schedule = EmployeesSchedule::create([
                'name' => $this->title,
                'team_id' => $this->team_id,
                'year' => $this->year
            ]);
        }

        $this->schedule_model = $schedule;

        foreach ($this->schedule_data as $month) {
            foreach ($month as $employee_id => $cells) {
                foreach ($cells as $date => $hours) {
                    $date_for_check = explode('-', $date);
                    if (!checkdate($date_for_check[1], $date_for_check[2], $date_for_check[0])) continue;

                    WorkingDay::updateOrCreate([
                        'schedule_id' => $this->schedule_model->id,
                        'user_id' => $employee_id,
                        'day' => Carbon::parse($date)
                    ], ['hours' => $hours]);

                }
            }
        }

        $this->saveBusy();

        if ($is_new) {
            return redirect()->to(route('employees.schedule'));
        }
    }

    protected function saveBusy()
    {
        foreach ($this->busy as $employee_id => $busy) {
            foreach ($busy as $date => $is_busy) {
                $carbon_date = Carbon::parse($date);

                $day = WorkingDay::where(
                    ['schedule_id' => $this->schedule_model->id,
                        'day' => (clone $carbon_date)->startOfDay(),
                        'user_id' => $employee_id
                    ])->firstOrFail();

                WorkingCell::updateOrCreate([
                    'day_id' => $day->id,
                    'start_period' => $carbon_date
                ], ['is_busy' => $is_busy]);
            }
        }
    }

    public function createBusy()
    {
        $employee_id = $this->choosed_employee->id;
        $this->busy[$employee_id] = array_merge($this->busy[$employee_id] ?? [], $this->busy_template);
        $this->hideAndClearBusy();
    }

    public function hideAndClearBusy()
    {
        $this->showModalBusy = false;
    }

    public function updateTheDayWorkingHours()
    {
        $hours = $this->getCurrentHoursBasedOnBusy();
        $this->hours_per_day = $hours > $this->base_hours_per_day ? $hours : $this->base_hours_per_day;
    }
}
