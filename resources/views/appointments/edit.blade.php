<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-semibold text-gray-800 leading-tight mr-4">
                @if($model->exists)
                    Детали визита {{$model->date}}
                @else
                    Создание визита {{$date->format('d.m.Y')}}
                @endif
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('appointments.appointment-edit', ['model' => $model, 'date' => $date])
        </div>
    </div>
</x-app-layout>
