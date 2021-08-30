<div>
    <div role="tab-container">
        <div role="tabs" class="flex">
            <div role="tab" class="flex-1 tab active" data-id="#main">
                Основное
            </div>
            <div role="tab" class="flex-1 tab border-1" data-id="#properties">
                Свойства характеристик
            </div>
        </div>
        <div role="tabpanels">
            <div role="tabpanel" class="active" id="main">
                <div class="grid grid-cols-1 mb-4">
                    @if($this->success)
                        <div class="bg-green-400 relative text-white py-3 px-3 rounded-lg mb-3">
                            {{ $this->success }}
                        </div>
                    @endif

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
                                inputEventName="set-select2-value"
                                outputEventName="change-category-value"
                            >
                                @if($category->parent)
                                    <option value="{{$category->parent->id}}" selected>{{$category->parent->name}}</option>
                                @endif
                            </x-vendor.selectable>
                        </div>
                    </div>
                </div>
                @if ($this->errors)
                    @foreach ($this->errors as $error)
                        <div class="bg-red-200 relative text-red-500 py-3 px-3 rounded-lg">
                            {{ $error[0] }}
                        </div>
                    @endforeach
                @endif
                <x-jet-button type="button"
                              class="mt-3"
                              wire:click="update">
                    {{ __('Save') }}
                </x-jet-button>
            </div>
            <div role="tabpanel" id="properties">
                <x-jet-button wire:click="$set('modals.addPropertyInCategory', true)">
                    {{__('Add')}}
                </x-jet-button>
                <x-jet-button wire:click="$set('modals.createPropertyInCategory', true)">
                    {{__('Create')}}
                </x-jet-button>
                @foreach($this->category->properties as $property)
                    <div class="my-3 bg-gray-100 rounded-xl py-10">
                        <div class="grid grid-cols-12 text-gray-600">
                            <div class="text-center">
                                <input type="checkbox" value="1" name="mass[]">
                            </div>
                            <div class="col-start-2 col-end-4">
                                {{$property->name}}
                            </div>
                            <div class="col-start-4 col-end-11">
                                {{$property->values->implode(' ')}}
                            </div>
                            <div class="col-start-11 col-end-13">
                                <svg class="cursor-pointer float-right mr-4" width="20" height="20" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.92634 2.16101H2.09474C1.8044 2.16101 1.52595 2.27635 1.32064 2.48165C1.11534 2.68696 1 2.96541 1 3.25575V10.9189C1 11.2093 1.11534 11.4877 1.32064 11.693C1.52595 11.8984 1.8044 12.0137 2.09474 12.0137H9.75794C10.0483 12.0137 10.3267 11.8984 10.532 11.693C10.7373 11.4877 10.8527 11.2093 10.8527 10.9189V7.08735" stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10.0316 1.34009C10.2493 1.12234 10.5447 1 10.8526 1C11.1606 1 11.4559 1.12234 11.6737 1.34009C11.8914 1.55785 12.0138 1.85319 12.0138 2.16115C12.0138 2.46911 11.8914 2.76445 11.6737 2.98221L6.47366 8.18223L4.28418 8.7296L4.83155 6.54012L10.0316 1.34009Z" stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-drawer max-width="max-w-6xl" :isOpen="$modals['addPropertyInCategory']" onClose="$toggle('modals.addPropertyInCategory')">
        <div class="overflow-y-auto px-4 sm:px-6">
            <div class="mb-3">
                <h2 class="text-lg font-medium text-gray-900">Добавить свойство</h2>
            </div>
            @livewire('nomenclature.property.add-property-in-category', ['category' => $this->category])
        </div>
    </x-drawer>
    <x-drawer max-width="max-w-6xl" :isOpen="$modals['createPropertyInCategory']" onClose="$toggle('modals.createPropertyInCategory')">
        <div class="overflow-y-auto px-4 sm:px-6">
            <div class="mb-3">
                <h2 class="text-lg font-medium text-gray-900">Создать свойство</h2>
            </div>
            @livewire('nomenclature.property.property-edit', ['property' => new App\Domains\Nomenclature\Models\NomenclatureProperty])
        </div>
    </x-drawer>
</div>

<script>
    addEventListener("change-category-value", function(event) {
        @this.set('parent_id', event.detail.value)
    })
</script>
