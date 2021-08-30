<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employees schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="bg-white bg-opacity-25 p-6">

                    <div class="mt-10 sm:mt-0">
                        <x-jet-action-section>
                            <x-slot name="title">
                                График
                            </x-slot>

                            <x-slot name="description"></x-slot>

                            <x-slot name="actions">
                                <a href="{{route('employees.schedule.create')}}">
                                    <x-jet-button type="button">
                                        {{ __('Creat') }}
                                    </x-jet-button>
                                </a>
                            </x-slot>


                            <x-slot name="content">
                                <div class="space-y-6">
                                    @if ($data->count() < 1)
                                        <div class="ml-4">{{ __('Not available') }}</div>
                                    @else
                                        @foreach ($data as $schedule_item)
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div class="ml-4">{{ $schedule_item->name }}</div>
                                                </div>

                                                <div class="flex items-center">
                                                    <div class="flex divide-x divide-gray-400"></div>
                                                    <div class="flex-initial px-2 text-right">
                                                        <a href=" {{ route('employees.schedule.update', $schedule_item->id)  }} ">
                                                            <x-jet-button>Редактировать</x-jet-button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mt-8">{{$data->links()}}</div>
                            </x-slot>
                        </x-jet-action-section>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
