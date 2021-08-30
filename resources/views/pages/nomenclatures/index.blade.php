<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h1 class="font-semibold text-gray-800 leading-tight mr-4">
                {{ __('nomenclatures.nomenclature') }}
            </h1>

            <x-jet-nav-link
                href="{{ route('nomenclatures.create') }}"
                :active="request()->routeIs('documents')"
                class="border-b-0 mr-2">
                {{__('nomenclatures.create')}}
            </x-jet-nav-link>

        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @livewire('nomenclature.nomenclature-index')
        </div>
    </div>
</x-app-layout>
