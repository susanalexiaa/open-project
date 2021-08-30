<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h1 class="font-semibold text-gray-800 leading-tight mr-4">
                @if($nomenclature->exists)
                    {{ __('nomenclatures.editing_nomenclature') }}
                @else
                    {{ __('nomenclatures.creating_nomenclature') }}
                @endif
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('nomenclature.nomenclature-edit', ['nomenclature' => $nomenclature])
        </div>
    </div>
</x-app-layout>
