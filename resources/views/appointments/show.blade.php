<x-app-layout>
    <x-slot name="header">
        <div class="flex items-left">
            <h1 class="font-bold text-gray-800 leading-tight">
                {{ $appointment->title }}
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-full mb-4 p-6 bg-white rounded shadow text-sm">
                <div class="flex flex-col md:flex-row justify-start md:justify-left md:items-left font-light text-gray-600 mb-2">
                    {{ $appointment->hours }} : {{ $appointment->minutes }} &nbsp;&nbsp; {{ \Carbon\Carbon::parse($appointment->date)->format('d.m.Y') }}
                </div>
                <div class="flex flex-col md:flex-row justify-start md:justify-left md:items-left font-bold text-gray-600 mb-2">
                    {{ $appointment->title }}
                </div>
                <div class="flex flex-col md:flex-row justify-end md:justify-right md:items-right font-light text-gray-600">
                    <a class="w-full md:w-32 px-2 py-1 bg-gray-600 text-gray-100 font-bold rounded hover:bg-gray-500 text-center" href={{ route("appointments.list") }}>
                        {{ __('appointments.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
