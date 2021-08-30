@section('css-dependencies')
    @parent
    <link rel="stylesheet" href="{{asset('vendor/flatpickr/flatpickr.min.css')}}">
@endsection
<div>
    <div class="grid grid-cols-1 mb-4">

        @if($this->success)
            <div class="bg-green-400 relative text-white py-3 px-3 rounded-lg mb-3">
                {{ $this->success }}
            </div>
        @endif

        <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('Name')}}</label>
        <x-jet-input
            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
            type="text"
            wire:model="name"
        >
        </x-jet-input>

        <label class="text-gray-800 block mb-1 mt-3 font-bold text-sm tracking-wide">{{__('Sort')}}</label>
        <x-jet-input
            class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
            type="text"
            wire:model="sort"
        >
        </x-jet-input>
    </div>
    @if ($this->errors)
        @foreach ($this->errors as $error)
            <div class="bg-red-200 relative text-red-500 py-3 px-3 rounded-lg">
                {{ $error[0] }}
            </div>
        @endforeach
    @endif
    <x-button-link
        class="mt-3"
        href="{{route('prices.index')}}">
        {{ __('Back') }}
    </x-button-link>

    <x-jet-button type="button"
                  class="mt-3"
                  wire:click="update">
        {{ __('Save') }}
    </x-jet-button>
    <div class="bg-white overflow-hidden border sm:rounded-lg mt-4">
        @if($this->priceType->id)
            <div class="bg-white bg-opacity-25 p-6">
                <div class="mt-10 sm:mt-0">
                    <div class="md:grid md:grid-cols-3 md:gap-6">
                        <div class="md:col-span-1">
                            <div class="px-4 sm:px-0">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{__('prices.prices.create')}}
                                </h3>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{__('prices.prices.description')}}
                                </p>
                                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">{{__('prices.prices.date')}}</label>
                                <x-jet-input
                                    class="bg-gray-100 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                                    type="text"
                                    wire:model="date"
                                    data-toggle="datepicker"
                                >
                                </x-jet-input>
                                <p class="mt-4 text-sm">
                                    <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"
                                       href="#"
                                       wire:click="createPrice"
                                    >
                                        {{__('Creat')}}
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="mt-5 md:mt-0 md:col-span-2 space-y-5">
                            @foreach($this->priceType->prices->sortByDesc('start_date') as $price)
                                <div class="">
                                    <div class="rounded shadow py-2">
                                        <div class="flex items-center flex-wrap">
                                            <div class="flex flex-auto items-center">
                                                <div class="ml-4">{{__('prices.prices.start_from')}} {{\Carbon\Carbon::parse($price->start_date)->format('d.m.Y')}}</div>
                                            </div>
                                            <div class="flex flex-wrap items-center">
                                                <div class="flex-initial my-2 px-2 text-right">
                                                    <a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"
                                                       href="{{route('prices.editPrice', $price)}}">
                                                        {{__('Edit')}}
                                                    </a>
                                                </div>
                                                <div class="flex-initial px-2 text-right">
                                                    <form action="{{route('prices.destroyPrice', $price)}}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <x-jet-button type="submit" class="cursor-pointer bg-red-600 text-white hover:bg-red-500 focus:border-red-700 focus:shadow-outline-red active:bg-red-600">
                                                            {{__('Delete')}}
                                                        </x-jet-button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</div>

@section('js-dependencies')
    @parent
    <script src="{{asset('vendor/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('vendor/flatpickr/ru.js')}}"></script>
    <script>
        flatpickrStart();
        window.addEventListener('rendering-list', event => {
            console.log(event);
            if (event.detail.new){
                flatpickrStart();
            }
        })
        function flatpickrStart(){
            flatpickr('[data-toggle="datepicker"]', {
                dateFormat: "d.m.Y",
                locale: "ru"
            });
        }
    </script>
@endsection
