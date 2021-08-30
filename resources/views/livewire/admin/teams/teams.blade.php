<div class="bg-white bg-opacity-25 p-6">

    {{--    data table--}}
    <div class="mt-10 sm:mt-0">
        <x-jet-action-section>

            <x-slot name="title">
                {{ __('teams.create') }}
            </x-slot>

            <x-slot name="description">
                {{ __('teams.description') }}
            </x-slot>

            <x-slot name="actions">
                <x-button-link
                    href="{{route('admin.teams.create')}}">
                    {{ __('Creat') }}
                </x-button-link>
            </x-slot>

            <x-slot name="content">
                <div class="space-y-3">
                    <x-jet-input
                        type="text"
                        name="query"
                        placeholder="Введите имя группы"
                        class="w-full"
                        wire:model.debounce.500ms="search"
                        autocomplete="off"
                    ></x-jet-input>
                    @if ($teams->count() < 1)
                        <div class="ml-4">{{ __('Not available') }}</div>
                    @else
                        @foreach ($teams as $team)
                            <div class="rounded shadow py-5">
                                <div class="flex items-center flex-wrap">
                                    <div class="flex flex-auto items-center">
                                        <div class="ml-4">{{ $team->name }}</div>
                                    </div>
                                    <div class="flex flex-wrap items-center">
                                        <div class="flex-initial my-2 px-2 text-right">
                                            <x-button-link
                                                href="{{route('admin.teams.edit', $team)}}">
                                                {{ __('teams.edit') }}
                                            </x-button-link>
                                        </div>
                                        <div class="flex-initial px-2 text-right">
                                            <x-button-link class="bg-red-600 text-white hover:bg-red-500 focus:border-red-700 focus:shadow-outline-red active:bg-red-600"
                                                           href="{{route('admin.teams.destroy', $team)}}">
                                                {{ __('Delete') }}
                                            </x-button-link>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 ml-2">
                                    <div class="flex divide-x divide-gray-400 flex-wrap">
                                        @foreach ($team->allUsers() as $member)
                                            <div class="flex-initial px-2 text-sm text-center text-gray-400">
                                                {{ $member->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="mt-8">{{$teams->links()}}</div>
            </x-slot>
        </x-jet-action-section>

    </div>
</div>
