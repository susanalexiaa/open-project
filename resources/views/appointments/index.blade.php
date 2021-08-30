<x-app-layout>
    <x-slot name="header">
        <div class="flex items-left">
            <h1 class="font-semibold text-gray-800 leading-tight mr-4">
                {{ __('appointments.appointment') }}
            </h1>

            <x-second-nav-link
                href="{{ route('appointments.create') }}"
                class="border-b-0 mr-2" data-entity="createLink">
                {{ __('appointments.create') }}
            </x-second-nav-link>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('appointments.appointment-index')
        </div>
    </div>
</x-app-layout>
