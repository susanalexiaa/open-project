<div>
    <div class="grid grid-cols-1 mb-4">
        <div class="space-y-5">
            <div>
                <div class="grid grid-cols-12 -mx-3">
                    <div class="col-start-1 col-end-7  px-3">
                        <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('nomenclatures.nomenclature')}}</label>
                        <div wire:ignore>
                            <div class="selectable-container">
                                <x-vendor.selectable
                                    :select2Url="url('/api/nomenclatures/products/getList')"
{{--                                    :viewAllUrl="route('nomenclature.list')"--}}
                                    placeholder="{{__('nomenclatures.nomenclature')}}"
                                    buttonText="Все"
                                    name="nomenclature_id"
                                    inputEventName="set-select2-value"
                                    outputEventName="change-select2-value"
                                >
                                </x-vendor.selectable>
                            </div>
                        </div>
                    </div>
                    <div class="col-start-7 col-end-13 px-3">
                        <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('prices.price')}}</label>
                        <x-jet-input
                            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            type="number"
                            wire:model="priceInput"
                        >
                        </x-jet-input>
                    </div>
                </div>
                <div>
                    <x-jet-button type="button"
                                  class="mt-3"
                                  wire:click="addPrice">
                        {{ __('prices.prices.add_price') }}
                    </x-jet-button>
                </div>
            </div>
            <div>
                @foreach($this->price->products as $product)
                    <div class="flex -mx-3 items-end">
                        <div class="flex-1 w-40 px-3 self-center">
                            {{--                            <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('nomenclatures.nomenclature')}}</label>--}}
                            {{$product->name}}
                            {{--                            <div wire:ignore>--}}
                            {{--                                <div class="selectable-container" @change-select2-value_{{$nomenclature->id}}.window="console.log($event)">--}}
                            {{--                                    <x-vendor.selectable--}}
                            {{--                                        :select2Url="url('/api/nomenclatures')"--}}
                            {{--                                        :viewAllUrl="route('nomenclature.list')"--}}
                            {{--                                        placeholder="{{__('nomenclatures.nomenclature')}}"--}}
                            {{--                                        buttonText="Все"--}}
                            {{--                                        name="nomenclature_id"--}}
                            {{--                                        inputEventName="set-select2-value_{{$nomenclature->id}}"--}}
                            {{--                                        outputEventName="change-select2-value_{{$nomenclature->id}}"--}}
                            {{--                                    >--}}
                            {{--                                        <option value="{{$nomenclature->id}}" selected>{{$nomenclature->name}}</option>--}}
                            {{--                                    </x-vendor.selectable>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                        <div class="flex-1 flex-grow px-3">
                            <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('prices.price')}}</label>
                            <x-jet-input
                                class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                                type="number"
                                wire:change.lazy="setPrice({{$product->id}}, $event.target.value)"
                                :value="$product->price->price"
                            >
                            </x-jet-input>
                        </div>
                        <div class="flex-grow-0 flex-shrink-0 ">
                            <x-jet-button
                                class="cursor-pointer bg-red-600 text-white hover:bg-red-500 focus:border-red-700 focus:shadow-outline-red active:bg-red-600"
                                wire:click="deletePrice({{$product->id}})"
                            >
                                {{__("Delete")}}
                            </x-jet-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @if($this->success)
        <div class="bg-green-400 relative text-white py-3 px-3 rounded-lg mb-3">
            {{ $this->success }}
        </div>
    @endif
    @if ($this->errors)
        @foreach ($this->errors as $error)
            <div class="bg-red-200 relative text-red-500 py-3 px-3 rounded-lg mt-3">
                {{ $error[0] }}
            </div>
        @endforeach
    @endif

    <x-button-link
        class="mt-3"
        href="{{route('prices.edit', $this->price->priceType)}}">
        {{ __('Back') }}
    </x-button-link>

    {{--    <x-jet-button type="button"--}}
    {{--                  class="mt-3"--}}
    {{--                  wire:click="$refresh">--}}
    {{--        {{ __('Save') }}--}}
    {{--    </x-jet-button>--}}

</div>

<script>
    addEventListener("change-select2-value", function(event) {
    @this.set('nomenclature_id', event.detail.value)
    })
    addEventListener("addPriceEvent", function() {
        // location.reload();
    })
    // window.livewire.hook('afterDomUpdate', () => {
    //     console.log('here');
    //     window.Alpine.discoverUninitializedComponents((el) => {
    //         window.Alpine.initializeComponent(el)
    //     })
    // })
</script>
@section('js-dependencies')
    @parent
    <script src="{{asset('vendor/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('vendor/flatpickr/ru.js')}}"></script>
    <script>
        flatpickr('[data-toggle="datepicker"]', {
            dateFormat: "d.m.Y",
            locale: "ru"
        });
    </script>
@endsection



