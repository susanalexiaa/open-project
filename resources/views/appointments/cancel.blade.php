<x-app-layout>
    <x-slot name="header">
        <div class="flex items-left">
            <h1 class="font-semibold text-gray-800 leading-tight mr-4">
                {{ __('appointments.appointment') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-2">
                <div class="p-2 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden">
                    <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">
                        <form action="{{route('appointments.cancel', $appointment->uuid)}}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 space-x-4">
                                <div class="flex">
                                    <h2 class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2">Отмена визита&nbsp</h2>
                                    <span class="flex-inline font-bold text-l mb-6 text-gray-800 border-b pb-2"></span>
                                </div>
                                <div class="inline-block w-full mb-4">
                                    <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Причина отмены</label>
                                    <div class="relative">
                                        <x-select
                                            class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700"
                                            required
                                            name="reason"
                                        >
                                            <option value="" disabled selected>Выберите причину отмены</option>
                                            <option value="Болезнь">Болезнь</option>
                                            <option value="Опоздание">Опоздание</option>
                                            <option value="Другое">Другое</option>
                                        </x-select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <span class="lg:inline-flex sm:flex-box sm:mt-0 mt-2 lg:justify-center lg:justify-center sm:items-left">
                                <div class="flex lg:ml-3 text-gray-500 items-center">
                                    <div class="flex flex-inline relative lg:mx-auto text-gray-600">
                                        <button
                                            type="submit"
                                            class="inline-flex items-right justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mr-2"
                                        >
                                            Сохранить
                                        </button>
                                        <x-button-link
                                            href="{{route('appointments.list')}}"
                                            type="button"
                                            class="inline-flex items-right justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5 mr-2"
                                        >
                                            Назад
                                        </x-button-link>
                                    </div>
                                </div>
                            </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
