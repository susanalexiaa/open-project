<div>
    <x-drawer max-width="max-w-6xl" :isOpen="$modals['mainModal']" onClose="$toggle('modals.mainModal')">
        <div class="overflow-y-auto px-4 sm:px-6">
            <div class="mb-3">
                @if($this->category->exists)
                    <h2 class="text-lg font-medium text-gray-900">Редактирование категории</h2>
                @else
                    <h2 class="text-lg font-medium text-gray-900">Создание категории</h2>
                @endif
            </div>
            <div>
                <div role="tab-container">
                    <div role="tabs" class="flex">
                        <div role="tab" class="flex-1 tab @if($this->activeTab === 'main') active @endif" data-id="#main" wire:click="$set('activeTab', 'main')">
                            Основное
                        </div>
                        @if($this->category->exists)
                            <div role="tab" class="flex-1 tab border-1 @if($this->activeTab === 'properties') active @endif" data-id="#properties" wire:click="$set('activeTab', 'properties')">
                                Свойства характеристик
                            </div>
                        @endif
                    </div>
                    <div role="tabpanels">
                        <div role="tabpanel" class="@if($this->activeTab === 'main') active @endif" id="main">
                            <div class="grid grid-cols-1 mb-4">

                                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('Name')}}</label>
                                <x-jet-input
                                    class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                                    type="text"
                                    wire:model="name"
                                >
                                </x-jet-input>
                                <label class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.parent_category')}}</label>

                                <div wire:ignore>
                                    <div class="selectable-container">
                                        <x-vendor.selectable
                                            :select2Url="url('/api/nomenclatures/categories/getList')"
                                            placeholder="{{__('nomenclatures.parent_category')}}"
                                            buttonText="Все"
                                            name="category_id"
                                            inputEventName="set-category-value"
                                            outputEventName="change-category-value"
                                        >
                                            @if($category->parent)
                                                <option value="{{$category->parent->id}}" selected>{{$category->parent->name}}</option>
                                            @endif
                                        </x-vendor.selectable>
                                    </div>
                                </div>
                            </div>
                            <x-jet-button type="button"
                                          class="mt-3"
                                          wire:click="update">
                                {{ __('Save') }}
                            </x-jet-button>
                        </div>
                        <div role="tabpanel" class="@if($this->activeTab === 'properties') active @endif" id="properties">
                            <x-jet-button wire:click="$emit('openAddPropertyInCategory', {{$this->category->id}})">
                                {{__('Add')}}
                            </x-jet-button>
                            <x-jet-button wire:click="$emit('openPropertyEditModal', 0)">
                                {{__('Create')}}
                            </x-jet-button>
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
                                            <input type="checkbox" value="{{$property->id}}" wire:model="mass">
                                        </div>
                                        <div class="col-start-2 col-end-6">
                                            {{$property->name}}
                                        </div>
                                        <div class="col-start-6 col-end-11">
                                            {{$property->values->implode('name', ', ')}}
                                        </div>
                                        <div class="col-start-11 col-end-13">
                                            <svg class="cursor-pointer float-right mr-4" wire:click="$emit('openPropertyEditModal', {{$property->id}})" width="20" height="20" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.92634 2.16101H2.09474C1.8044 2.16101 1.52595 2.27635 1.32064 2.48165C1.11534 2.68696 1 2.96541 1 3.25575V10.9189C1 11.2093 1.11534 11.4877 1.32064 11.693C1.52595 11.8984 1.8044 12.0137 2.09474 12.0137H9.75794C10.0483 12.0137 10.3267 11.8984 10.532 11.693C10.7373 11.4877 10.8527 11.2093 10.8527 10.9189V7.08735" stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M10.0316 1.34009C10.2493 1.12234 10.5447 1 10.8526 1C11.1606 1 11.4559 1.12234 11.6737 1.34009C11.8914 1.55785 12.0138 1.85319 12.0138 2.16115C12.0138 2.46911 11.8914 2.76445 11.6737 2.98221L6.47366 8.18223L4.28418 8.7296L4.83155 6.54012L10.0316 1.34009Z" stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {!! $properties->links() !!}
                            <div class="grid grid-cols-12">
                                <div class="col-start-1 col-end-4">
                                    <x-select wire:change="changeMassSelect($event.target.value)">
                                        <option value="" selected disabled>Массовые действия</option>
                                        <option value="detach">Открепить</option>
                                    </x-select>
                                </div>
                                <div class="col-start-4 col-end-7 text-sm">
                                    <label class="cursor-pointer">
                                        <input type="checkbox" wire:model="forAllCheckbox"> Для всех
                                    </label>
                                    <p>
                                         Внимание! При выборе данной опции будут выбраны элементы на всех страницах.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                addEventListener("change-category-value", function(event) {
                    @this.set('parent_id', event.detail.value)
                })
            </script>
        </div>
    </x-drawer>
    @livewire('nomenclature.property.add-property-in-category')
    @livewire('nomenclature.property.property-edit')


</div>
