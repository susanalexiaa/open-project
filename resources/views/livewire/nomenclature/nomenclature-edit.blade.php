@section('css-dependencies')
    @parent
@endsection

<div class="px-2 py-4 md:px-4 md:py-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div role="tab-container">
        <div role="tabs" class="flex">
            <div role="tab" class="flex-1 tab @if($activeTab === 'main') active @endif" data-id="#main"
                 wire:click="$set('activeTab', 'main')">
                Основное
            </div>
            @if($nomenclature->exists)
                <div role="tab" class="flex-1 tab border-1 @if($activeTab === 'properties') active @endif"
                     data-id="#properties" wire:click="$set('activeTab', 'properties')">
                    Характеристики
                </div>

            @endif

            <div role="tab" class="flex-1 tab border-1 @if($activeTab === 'access') active @endif" data-id="#access"
                 wire:click="$set('activeTab', 'access')">
                Права доступа
            </div>

        </div>
        <div role="tabpanels">
            <div role="tabpanel" class="@if($activeTab === 'main') active @endif" id="main">
                <div class="grid grid-cols-1 mb-4">
                    <label
                        class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('nomenclatures.code')}}</label>
                    <x-jet-input
                        class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                        type="text"
                        wire:model="code"
                    >
                    </x-jet-input>

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.article')}}</label>
                    <x-jet-input
                        class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                        type="text"
                        wire:model="article"
                    >
                    </x-jet-input>

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.barcode')}}</label>
                    <x-jet-input
                        class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                        type="text"
                        wire:model="barcode"
                    >
                    </x-jet-input>

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.category')}}</label>
                    <div wire:ignore>
                        <div class="selectable-container">
                            <x-vendor.selectable
                                :select2Url="url('/api/nomenclatures/categories/getList')"
                                {{--                    :viewAllUrl="route('nomenclature.category.list')"--}}
                                placeholder="{{__('nomenclatures.category')}}"
                                multiple="multiple"
                                buttonText="Все"
                                name="category_id"
                                inputEventName="set-select2-value"
                                outputEventName="change-category-value"
                            >
                                @foreach($nomenclature->categories as $item)
                                    <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                @endforeach
                            </x-vendor.selectable>
                        </div>
                    </div>

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.name')}}</label>
                    <x-jet-input
                        class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                        type="text"
                        wire:model="name"
                    >
                    </x-jet-input>

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.description')}}</label>
                    <div wire:ignore>
                        @trix(Domain\Nomenclature\Models\Nomenclature::class, 'description')
                    </div>

                    @if($nomenclature->getFirstMediaUrl('image'))
                        <div class="mt-3">
                            <img src="{{$nomenclature->getFirstMediaUrl('image')}}" alt=""
                                 class="max-w-lg max-h-96 border shadow">
                            <x-jet-button
                                wire:click="deletePhoto"
                            >
                                Удалить изображение
                            </x-jet-button>
                        </div>
                    @endif

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.upload_photo')}}</label>
                    <x-jet-input
                        class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                        type="file"
                        wire:model="file"
                    >
                    </x-jet-input>


                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.measure_unit')}}</label>
                    <x-select wire:model="measureUnit">
                        @foreach($measureUnits as $item)
                            <option value="{{$item['id']}}"
                                    @if($measureUnit === $item['id']) selected @endif>{{$item['name']}}</option>
                        @endforeach
                    </x-select>

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.vat_rate')}}</label>
                    <x-select wire:model="vatRate">
                        @foreach($vatRates as $item)
                            <option value="{{$item['id']}}"
                                    @if($measureUnit === $item['id']) selected @endif>{{$item['name']}}</option>
                        @endforeach
                    </x-select>

                    <label
                        class="text-gray-800 block mb-1 mt-4 font-bold text-sm tracking-wide">{{__('nomenclatures.extract_to_mobile')}}</label>
                    <x-jet-checkbox wire:model="extract_to_mobile"/>

                </div>
                <x-button-link
                    class="mt-3"
                    href="{{route('nomenclatures.index')}}">
                    {{ __('Back') }}
                </x-button-link>
                <x-jet-button type="button"
                              class="mt-3"
                              wire:click="update">
                    {{ __('Save') }}
                </x-jet-button>
            </div>
            @if($nomenclature->exists)
                <div role="tabpanel" class="@if($activeTab === 'properties') active @endif" id="properties">
                    <x-jet-button wire:click="createProduct">
                        Создать
                    </x-jet-button>
                    <x-jet-button wire:click="generateProducts">
                        Сгенерировать
                    </x-jet-button>
                    <div class="flex my-3">
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
                    <div>
                        @foreach($this->filters as $filterKey => $filter)
                            @if(!empty($filter['values']))
                                <div class="inline-block rounded-2xl border-2 border-blue-300 p-1 bg-blue-100">
                                    {{$filter['name']}} - {{$filter['text']}} <span class="text-xl cursor-pointer"
                                                                                    wire:click="deleteFilter({{$filterKey}})">x</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div>
                        <a href="#" wire:click="$set('modals.addFilter', true)">Добавить условие фильтра</a>
                    </div>
                    <div class="py-2">
                        <label class="text-gray-600">
                            <input type="checkbox" wire:model="selectAllProperties">
                            Выделение элементов на странице
                        </label>
                    </div>
                    @foreach($products as $product)
                        <div class="my-3 bg-gray-100 rounded-xl py-10">
                            <div class="grid grid-cols-12 text-gray-600">
                                <div class="text-center">
                                    <input type="checkbox" value="{{$product->id}}" wire:model="mass">
                                </div>
                                <div class="col-start-2 col-end-6">
                                    {{$product->name}}
                                </div>
                                <div class="col-start-6 col-end-11">
                                    {{$product->values->implode('name', ', ')}}
                                </div>
                                <div class="col-start-11 col-end-13">
                                    <svg class="cursor-pointer float-right mr-4"
                                         wire:click="editProduct({{$product->id}})" width="20" height="20"
                                         viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.92634 2.16101H2.09474C1.8044 2.16101 1.52595 2.27635 1.32064 2.48165C1.11534 2.68696 1 2.96541 1 3.25575V10.9189C1 11.2093 1.11534 11.4877 1.32064 11.693C1.52595 11.8984 1.8044 12.0137 2.09474 12.0137H9.75794C10.0483 12.0137 10.3267 11.8984 10.532 11.693C10.7373 11.4877 10.8527 11.2093 10.8527 10.9189V7.08735"
                                            stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path
                                            d="M10.0316 1.34009C10.2493 1.12234 10.5447 1 10.8526 1C11.1606 1 11.4559 1.12234 11.6737 1.34009C11.8914 1.55785 12.0138 1.85319 12.0138 2.16115C12.0138 2.46911 11.8914 2.76445 11.6737 2.98221L6.47366 8.18223L4.28418 8.7296L4.83155 6.54012L10.0316 1.34009Z"
                                            stroke="#D2D6DC" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {!! $products->links() !!}
                    <div class="grid grid-cols-12">
                        <div class="col-start-1 col-end-4">
                            <x-select wire:change="changeMassSelect($event.target.value)">
                                <option value="" selected disabled>Массовые действия</option>
                                <option value="delete">Удалить</option>
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
            @endif
            <div role="tabpanel" class="@if($activeTab === 'access') active @endif" id="access">
                <div class="text-lg font-bold">{{Auth::user()->currentTeam->name}}</div>
                @foreach( Auth::user()->currentTeam->allUsers() as $user )
                    <div class="pl-2"><label class="flex items-center">
                            <x-jet-checkbox wire:model="access.{{$user->id}}"/>
                            <span class="pl-2">{{ $user->name }}</span> </label></div>
                @endforeach

                <div class="mt-2">
                    <x-jet-button class="mt-3" wire:click="saveAccess">
                        Сохранить
                    </x-jet-button>
                </div>
            </div>

        </div>
    </div>
    <x-drawer max-width="max-w-6xl" :isOpen="$modals['createProperty']" onClose="$toggle('modals.createProperty')">
        <div class="overflow-y-auto px-4 sm:px-6">
            <div class="mb-3">
                Создание характеристики
            </div>
            <div>
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('Name')}}</label>
                <x-jet-input
                    class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                    type="text"
                    wire:model="propArr.name"
                >
                </x-jet-input>
                <div class="mt-3">
                    <x-jet-label class="inline-block">
                        <x-jet-checkbox wire:model="propArr.export">

                        </x-jet-checkbox>
                        Экспортировать
                    </x-jet-label>

                </div>

                @foreach($this->getProperties() as $property)
                    <label
                        class="text-gray-800 block mb-1 font-bold text-sm tracking-wide mt-3">{{$property->name}}</label>
                    <div wire:ignore>
                        <div class="selectable-container">
                            <x-vendor.selectable
                                :select2Url="url('/api/nomenclature/properties/values/?propertyId=' . $property->id)"
                                placeholder="{{$property->name}}"
                                buttonText="Все"
                                name="property_{{$property->id}}"
                                inputEventName="set-property-{{$property->id}}-value"
                                outputEventName="change-property_{{$property->id}}-value"
                            >
                            </x-vendor.selectable>
                        </div>
                    </div>
                    <script>
                        addEventListener("change-property_{{$property->id}}-value", function (event) {
                        @this.set('propArr.props.{{$property->id}}', event.detail.value)
                        })
                    </script>
                @endforeach
                <label class="text-gray-800 block mb-1 mt-3 font-bold text-sm tracking-wide">{{__('Sort')}}</label>
                <x-jet-input
                    class="appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                    type="text"
                    wire:model="propArr.sort"
                ></x-jet-input>
                <x-jet-button class="mt-3" wire:click="saveProduct">
                    Сохранить
                </x-jet-button>
            </div>
        </div>
    </x-drawer>
    <x-drawer max-width="max-w-6xl" :isOpen="$modals['generateProperty']" onClose="$toggle('modals.generateProperty')">
        <div class="overflow-y-auto px-4 sm:px-6">
            <div class="mb-3">
                Сгенерировать характеристики
            </div>
            <div>
                @foreach($this->generatingProps as $propKey => $property)
                    <div class="grid grid-cols-12 my-2">
                        <div class="col-start-1 col-end-4">
                            {{$property['name']}}
                        </div>
                        <div class="col-start-4 col-end-6">
                            <x-select wire:model="generatingProps.{{$propKey}}.condition">
                                <option value="1">Равно</option>
                                <option value="2">Не равно</option>
                            </x-select>
                        </div>
                        <div wire:ignore class="col-start-6 col-end-13">
                            <div class="flex">
                                <div class="selectable-container flex-1">
                                    <x-vendor.selectable
                                        :select2Url="url('/api/nomenclature/properties/values/?propertyId=' . $property['id'])"
                                        placeholder="{{$property['name']}}"
                                        buttonText="Все"
                                        name="property_{{$property['id']}}"
                                        multiple="multiple"
                                        inputEventName="set-generate-property-{{$property['id']}}-value"
                                        outputEventName="change-generate-property_{{$property['id']}}-value"
                                    >
                                    </x-vendor.selectable>
                                </div>
                                <x-jet-button class="rounded-l-none rounded-r-none"
                                              wire:click="selectAllPropertiesInGenerate({{$property['id']}}, 'set-generate-property-{{$property['id']}}-value')">
                                    Выбрать все
                                </x-jet-button>
                                <x-jet-button class="rounded-l-none bg-blue-500 hover:bg-blue-600"
                                              wire:click="clearPropertiesInGenerate('set-generate-property-{{$property['id']}}-value')">
                                    Очистить
                                </x-jet-button>
                            </div>

                        </div>
                        <script>
                            addEventListener("change-generate-property_{{$property['id']}}-value", function (event) {
                            @this.set('generatingProps.{{$propKey}}.values', event.detail.value)
                            })

                        </script>
                    </div>
                @endforeach
                <x-jet-button class="mt-3" wire:click="startGenerateProducts" wire:loading.attr="disabled">
                    Сгенерировать
                </x-jet-button>
            </div>
        </div>
    </x-drawer>
    <x-drawer max-width="max-w-4xl" :isOpen="$modals['addFilter']" onClose="$toggle('modals.addFilter')">
        <div class="overflow-y-auto px-4 sm:px-6">
            <div class="mb-3">
                Добавить условие фильтра
            </div>
            <div>
                @foreach($this->getProperties() as $property)
                    <label
                        class="text-gray-800 block mb-1 font-bold text-sm tracking-wide mt-3">{{$property->name}}</label>
                    <div wire:ignore>
                        <div class="selectable-container">
                            <x-vendor.selectable
                                :select2Url="url('/api/nomenclature/properties/values/?propertyId=' . $property->id)"
                                placeholder="{{$property->name}}"
                                buttonText="Все"
                                name="property_{{$property->id}}"
                                multiple="multiple"
                                inputEventName="set-filter-property-{{$property->id}}-value"
                                outputEventName="change-filter-property_{{$property->id}}-value"
                            >
                            </x-vendor.selectable>
                        </div>
                    </div>
                    <script>
                        addEventListener("change-filter-property_{{$property->id}}-value", function (event) {
                        @this.call('addFilter', {{$property->id}}, event.detail.value)
                        })
                    </script>
                @endforeach
            </div>
        </div>
    </x-drawer>
</div>
<script>

    let content = '';

    @if(isset($nomenclature))
        content = '{!! $nomenclature->trixRichText()->first() ? $nomenclature->trixRichText()->first()->content : '' !!}';
    @endif
    $('#nomenclature-description-new-model').val(content)
    addEventListener("trix-change", function (event) {
    @this.set('description.content', $("#nomenclature-description-new-model").val())
    @this.set('description.attachment', $("#attachment-nomenclature-description-new-model").val())
    })

    addEventListener("change-category-value", function (event) {
    @this.set('category', event.detail.value)
    })


</script>
