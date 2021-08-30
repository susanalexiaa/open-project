<x-app-layout>
    <x-slot name="header">
        <div class="flex items-left">
            <h1 class="font-bold text-gray-800 leading-tight">
                {{ __('appointments.create') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-full mb-4 p-6 bg-white rounded shadow text-sm">
                <form method="POST" action="{{ route('appointments.store') }}">
                    @csrf
                    <x-jet-label for="title" value="{{ __('appointments.title') }}" />
                    <textarea id="body" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="title" :value="old('title')" required></textarea>

                    <x-jet-label for="date" value="{{ __('appointments.date') }}" />
                    <x-jet-input id="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="date" :value="old('date')" required></x-jet-input>

                    <x-jet-label for="hours" value="{{ __('appointments.hours') }}" />
                    <x-jet-input id="hours" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="hours" :value="old('hours')" required></x-jet-input>

                    <x-jet-label for="minutes" value="{{ __('appointments.minutes') }}" />
                    <x-jet-input id="minutes" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-2" name="minutes" :value="old('minutes')" required></x-jet-input>

                    <div class="flex flex-col md:flex-row justify-end md:justify-right md:items-right font-light text-gray-600">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button type="submit" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mr-2">
                                {{ __('appointments.save') }}
                            </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                {{ __('appointments.cancel') }}
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
