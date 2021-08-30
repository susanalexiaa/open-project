<div>
    <x-drawer max-width="max-w-4xl" :isOpen="$modals['mainModal']" onClose="$toggle('modals.mainModal')">
        <div class="overflow-y-auto px-4 sm:px-6">
            <div class="mb-3">
                <h2 class="text-lg font-medium text-gray-900">Добавить свойство</h2>
            </div>
            <div>
                <div>
                    <div class="flex">
                        <x-jet-input
                            type="text"
                            wire:model="search"
                            class="flex-1 rounded-r-none rounded-br-none"
                            placeholder="Поиск по названию"
                        >
                        </x-jet-input>
                        <x-jet-button class="rounded-l-none" wire:click="resetFilter">
                            Сбросить
                        </x-jet-button>
                    </div>


                </div>
                <div class="py-2">
                    <label class="text-gray-600">
                        <input type="checkbox" wire:model="selectAllProperties">
                        Выделение элементов на странице
                    </label>
                </div>
                @foreach($properties as $property)
                    <div class="my-3 bg-gray-100 rounded-xl py-10">
                        <div class="grid grid-cols-12 text-gray-600">
                            <div class="text-center">
                                <input type="checkbox" value="{{$property->id}}" wire:model="checkboxes">
                            </div>
                            <div class="col-start-2 col-end-6">
                                {{$property->name}}
                            </div>
                            <div class="col-start-6 col-end-11">
                                {{$property->values->implode('name', ', ')}}
                            </div>
                            <div class="col-start-11 col-end-13">
                            </div>
                        </div>
                    </div>
                @endforeach
                {!! $properties->links() !!}
                <x-jet-button wire:click="addProperties">
                    Добавить
                </x-jet-button>
            </div>
        </div>
    </x-drawer>

</div>
