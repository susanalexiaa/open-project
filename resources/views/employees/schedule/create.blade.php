<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $schedule->name ?? 'Новый график' }}</h2>
    </x-slot>

    @livewire('employees.schedule.create', ['schedule_model' => $schedule ?? null])
</x-app-layout>
