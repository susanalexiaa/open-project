<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="bg-white bg-opacity-25 p-6">
                <div class="mt-10 sm:mt-0">
                    <div>
                        <x-jet-label for="title" value="Название графика:"/>
                        <x-jet-input id="title" required wire:model="title" type="text" class="mt-1 block w-full"/>
                        <x-jet-input-error for="title" class="mt-2"/>
                    </div>

                    <div class="mt-5">
                        <x-jet-label for="year" value="Год:"/>
                        <div class="flex items-end">
                            <div>
                                <x-jet-input type="number" id="year" wire:model="year" wire:change="changeYear"
                                             type="text"
                                             class="mt-1 block w-full" :disabled="isset($schedule_model)" />
                            </div>
                            <div class="ml-2">
                                <x-jet-button class="py-3" wire:click="openAutocomplete"
                                              type="button">
                                    Запланировать график
                                </x-jet-button>
                            </div>
                        </div>
                        <x-jet-input-error for="year" class="mt-2"/>
                    </div>

                    <div class="schedule mt-5 overflow-scroll">
                        <div class="title py-5 px-3 bg-gray-200 flex rounded-xl">
                            <div class="w-2/12 flex-shrink-0">Сотрудник / Число</div>
                            <div class="dates flex">
                                @for($i = 1; $i <= 31; $i++)
                                    <div class="dayCell mx-3">{{$i}}</div>
                                @endfor
                            </div>
                        </div>

                        @foreach( $schedule_data as $key => $item )
                            <div class="month mt-5">
                                <?php $month = \Carbon\Carbon::create()->day(1)->month($key + 1)->translatedFormat('F'); ?>
                                <div class="text-xl font-bold px-3 month-name">{{ $month . ' ' .$year  }}</div>
                                @foreach($item as $employee_id => $employee)
                                    <div class="item mt-2 py-5 px-3 bg-gray-200 flex rounded-xl">
                                        <div
                                            class="w-2/12 flex-shrink-0 bg-white px-2 rounded-md py-1">{{ \App\Models\User::find($employee_id)->name }}</div>
                                        <div class="flex">
                                            @foreach($employee as $cell_date => $cell)
                                                @if( !is_null($cell) )
                                                    <div wire:click="openModalBusy({{$employee_id}}, '{{$cell_date}}', {{$cell}})"
                                                        class="@if( intval($cell) > 0 ) cursor-pointer @endif mx-3 bg-white rounded-md flex items-center dayCell justify-center">{{ $cell  }}</div>
                                                @else
                                                    <div class="mx-3 bg-white rounded-md flex items-center dayCell justify-center">x</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="text-right mt-3">
                        <x-jet-button wire:click="saveSchedule" wire:loading.attr="disabled">{{__('Save')}}</x-jet-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-jet-dialog-modal wire:model="showModalAutocomplite">
        <x-slot name="title">Запланировать график</x-slot>
        <x-slot name="content">
            <div class="flex items-end">
                <x-jet-button class="py-3" wire:click="addDay">Добавить</x-jet-button>
                <div class="ml-2">
                    <x-jet-label for="employee" value="Сотрудник"/>
                    <select id="employee" wire:model="employee_id" class="select bg-white select42Height">
                        <option value="0">Выбрать сотрудника</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ml-2">
                    <x-jet-label for="date_start" value="Дата отсчета:"/>
                    <x-jet-input class="block w-full datepicker" type="text" wire:model="date_start"/>
                </div>
            </div>

            @if ($errors->any())
                <div class="error text-red-500 mt-2">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if( count($days) )
                <div class="grid px-2 grid-cols-2 mt-5">
                    <span>День</span>
                    <span>Часы</span>
                </div>
            @endif
            @foreach($days as $day)
                <div class="grid grid-cols-2 bg-gray-200 px-2 py-2 rounded-md mt-2">
                    <x-jet-input class="w-3/12" type="text" disabled value="{{$loop->iteration}}"/>
                    <x-jet-input class="w-3/12" type="text" wire:model="days.{{$loop->index}}"/>
                </div>
            @endforeach
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="hideAndClearAutocomplete" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="createAutoComplete" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>

        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="showModalBusy">
        <x-slot name="title">Время работы</x-slot>
        <x-slot name="content">
            <div class="grid grid-cols-3">
                <div>Дата: {{ $choosed_date ? $choosed_date->format('d.m.Y') : '' }}</div>
                <div>Итого: {{ $hours_per_day }} часов</div>
            </div>

            <div class="grid grid-cols-3 mt-2 text-sm">
                <div>{{ $choosed_employee->name ?? ''}}</div>
                <div>Занятость</div>
            </div>
            @if( $choosed_date )
                @foreach( $busy_template as $date => $value )
                    <?php $date_carbon = \Carbon\Carbon::parse($date); ?>
                    <div class="grid grid-cols-3 mt-2 bg-gray-200 py-3 px-5 rounded-md">
                        <div class="pl-1">{{ $date_carbon->format('H:i') }}</div>
                        <div class="pl-4">
                            <x-jet-checkbox class="cursor-pointer" wire:click="updateTheDayWorkingHours" wire:model="busy_template.{{$date_carbon->format('Y-m-d H:i')}}"/>
                        </div>
                    </div>
                @endforeach
            @endif

        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="hideAndClearBusy" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="createBusy" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>

        </x-slot>
    </x-jet-dialog-modal>
</div>

