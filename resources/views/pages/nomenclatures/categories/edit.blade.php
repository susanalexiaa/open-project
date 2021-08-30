<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('nomenclatures.categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-white bg-opacity-25 p-6">
                    @livewire('nomenclature.nomenclature-category-edit', ['category' => $category])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
